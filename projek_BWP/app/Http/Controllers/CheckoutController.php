<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TicketInstance;
use App\Models\User;
use Carbon\Traits\Timestamp;
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

    public function Orders(Request $request)
    {
        $user_id = Auth::id();
        try {
            DB::beginTransaction();

            // Create order
            $order = new Order([
                'user_id' => $user_id,
                'order_date' => now(),
                'total_price' => $request->total_price,
            ]);
            $order->save();

            // Insert items dengan loop
            foreach ($request->items as $item) {
                $orderItem = new OrderItem([
                    'order_id' => $order->order_id,
                    'product_id' => $item['product_id'],
                    'schedule_id' => $item['schedule_id'],
                    'seat_id' => $item['seat_id'],
                    'price' => $item['price'],
                ]);
                $orderItem->save();

                // Insert ticket instance untuk item
                $ticketInstance = new TicketInstance([
                    'order_item_id' => $orderItem->order_item_id,
                    'user_id' => $user_id,
                    'qr_code' => $this->generateQRCode(),
                    'status' => 'active',
                    'valid_until' => now()->addDays(365),
                ]);
                $ticketInstance->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order_id' => $order->order_id,
                'user_id' => $user_id,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage(),
                'user_id' => $user_id
            ], 500);
        }
    }
}
