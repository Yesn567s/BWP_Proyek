<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    function index(){
        return null;
    }

    function cart(Request $request){
        $cart = $request->input('cart');
        return response()->json(['cart' => $cart]);
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
            foreach ($validated['items'] as $item) {
                $unitPrice = $item['price'];
                $quantity = $item['quantity'] ?? 1;

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
                }
            }

            if (!empty($items)) {
                DB::table('order_items')->insert($items);
            }

            return response()->json(['order_id' => $orderId], 201);
        });
    }
}
