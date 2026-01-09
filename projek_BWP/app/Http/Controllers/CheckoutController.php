<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TicketInstance;
use App\Models\User;
use Carbon\Traits\Timestamp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Session;
use function Laravel\Prompts\error;



class CheckoutController extends Controller
{
    function index(){
        return null;
    }

    function voucher($id){
        $voucher = DB::table('vouchers')->where('code', $id)->first();
        if($voucher){
            return response()->json([
                'voucher_id' => $voucher->voucher_id,
                'code' => $voucher->code,
                'description' => $voucher->description,
                'discount_type' => $voucher->discount_type,
                'discount_value' => $voucher->discount_value,
                'is_active' => $voucher->is_active,
            ]);
        }
        return throw new \Exception('Voucher not found');
    }

    private function generateQRCode()
    {
        return 'QR-' . strtoupper(Str::random(16));
    }

    public function orders()
    {
        $userId = Auth::id() ?: session('user_id');
        if (!$userId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $orders = DB::table('orders')
            ->where('user_id', $userId)
            ->orderBy('order_date', 'desc')
            ->orderBy('order_id', 'desc')
            ->get();

        return response()->json(['orders' => $orders]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items'                  => 'required|array|min:1',
            'items.*.schedule_id'    => 'nullable|integer|required_without:items.*.product_id',
            'items.*.product_id'     => 'nullable|integer|required_without:items.*.schedule_id',
            'items.*.seat_id'        => 'nullable|integer',
            'items.*.price'          => 'required|numeric|min:0',
            'items.*.quantity'       => 'nullable|integer|min:1',
            'items.*.valid_until'    => 'nullable|date',
            'total_price'            => 'required|numeric|min:0',
            'voucher_id'             => 'nullable|integer',
            'discount_amount'        => 'nullable|numeric|min:0',
        ]);

        $userId = Auth::id() ?: session('user_id');
        if (!$userId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $itemsCollection = collect($validated['items']);

        $scheduleIds = $itemsCollection
            ->pluck('schedule_id')
            ->filter()
            ->unique()
            ->all();

        $productIds = $itemsCollection
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->all();

        $scheduleProducts = DB::table('schedules')
            ->whereIn('schedule_id', $scheduleIds)
            ->pluck('product_id', 'schedule_id');

        foreach ($scheduleIds as $sid) {
            if (!isset($scheduleProducts[$sid])) {
                return response()->json(['error' => 'Invalid schedule_id: ' . $sid], 422);
            }
        }

        if (!empty($productIds)) {
            $existingProducts = DB::table('ticket_products')
                ->whereIn('product_id', $productIds)
                ->pluck('product_id')
                ->all();

            $missing = array_diff($productIds, $existingProducts);
            if (!empty($missing)) {
                return response()->json(['error' => 'Invalid product_id: ' . implode(', ', $missing)], 422);
            }
        }

        return DB::transaction(function () use ($validated, $userId, $scheduleProducts) {
            $orderId = DB::table('orders')->insertGetId([
                'user_id'     => $userId,
                'order_date'  => now(),
                'total_price' => $validated['total_price'],
            ]);

            $items = [];
            $itemValidUntils = []; // Track valid_until per inserted item index
            $itemIndex = 0;
            
            foreach ($validated['items'] as $item) {
                $unitPrice = $item['price'];
                $quantity = $item['quantity'] ?? 1;
                $itemValidUntil = $item['valid_until'] ?? null;

                $productId = $item['product_id'] ?? null;
                if (!$productId && isset($item['schedule_id'])) {
                    $productId = $scheduleProducts[$item['schedule_id']] ?? null;
                }

                // Expand quantity into individual order_items rows
                for ($i = 0; $i < $quantity; $i++) {
                    $items[] = [
                        'order_id'    => $orderId,
                        'product_id'  => $productId,
                        'schedule_id' => $item['schedule_id'] ?? null,
                        'seat_id'     => $item['seat_id'] ?? null,
                        'price'       => $unitPrice,
                    ];
                    $itemValidUntils[$itemIndex] = $itemValidUntil;
                    $itemIndex++;
                }
            }

            if (!empty($items)) {
                DB::table('order_items')->insert($items);
            }

            // Create ticket instances for all items and set validity windows
            $ticketableItems = DB::table('order_items as oi')
                ->join('ticket_products as tp', 'oi.product_id', '=', 'tp.product_id')
                ->leftJoin('schedules as s', 'oi.schedule_id', '=', 's.schedule_id')
                ->where('oi.order_id', $orderId)
                ->select('oi.order_item_id', 'tp.category_id', 's.start_datetime')
                ->orderBy('oi.order_item_id', 'asc')
                ->get();

            if ($ticketableItems->isNotEmpty()) {
                $instances = $ticketableItems->values()->map(function ($row, $idx) use ($userId, $itemValidUntils) {
                    $validUntil = null;

                    // First, check if valid_until was passed from frontend
                    if (!empty($itemValidUntils[$idx])) {
                        $validUntil = Carbon::parse($itemValidUntils[$idx])->endOfDay();
                    }
                    // For food/beverage (category 8), use 3 days
                    elseif ((int) $row->category_id === 8) {
                        $validUntil = Carbon::now()->addDays(3);
                    }
                    // For scheduled items (movies), use schedule date
                    elseif (!empty($row->start_datetime)) {
                        $validUntil = Carbon::parse($row->start_datetime)->endOfDay();
                    }

                    return [
                        'order_item_id' => $row->order_item_id,
                        'user_id'       => $userId,
                        'qr_code'       => $this->generateQRCode(),
                        'is_used'       => 0,
                        'status'        => 'active',
                        'valid_until'   => $validUntil,
                        'used_at'       => null,
                    ];
                })->all();

                DB::table('ticket_instances')->insert($instances);
            }

            return response()->json([
                'order_id' => $orderId,
                'success'  => true,
            ], 201);
        });
    }
}
