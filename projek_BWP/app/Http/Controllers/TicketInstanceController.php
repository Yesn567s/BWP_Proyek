<?php

namespace App\Http\Controllers;

use App\Models\TicketInstance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketInstanceController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->session()->get('user_id') ?? $request->cookie('user_id');
        if (!$userId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Seat / schedule tickets
        $tickets = DB::table('ticket_instances as t')
            ->join('order_items as o', 't.order_item_id', '=', 'o.order_item_id')
            ->join('orders as ord', 'o.order_id', '=', 'ord.order_id')
            ->join('ticket_products as tp', 'o.product_id', '=', 'tp.product_id')
            ->join('ticket_categories as tc', 'tp.category_id', '=', 'tc.category_id')
            ->leftJoin('schedules as s', 'o.schedule_id', '=', 's.schedule_id')
            ->leftJoin('seats', 'o.seat_id', '=', 'seats.seat_id')
            ->where('ord.user_id', $userId)
            ->select(
                't.ticket_instance_id as id',
                'tp.name as title',
                'seats.seat_number as seatNumber',
                DB::raw("COALESCE(t.status, 'active') as status"),
                'tc.category_name as category_name',
                't.qr_code as qr_value',
                't.valid_until as valid_until',
                'ord.order_date as order_date'
            )
            ->get();

        $foodOrders = DB::table('ticket_instances as t')
            ->join('order_items as oi', 't.order_item_id', '=', 'oi.order_item_id')
            ->join('orders as ord', 'oi.order_id', '=', 'ord.order_id')
            ->join('ticket_products as tp', 'oi.product_id', '=', 'tp.product_id')
            ->leftJoin('ticket_categories as tc', 'tp.category_id', '=', 'tc.category_id')
            ->where('ord.user_id', $userId)
            ->where('tp.category_id', 8)
            ->select(
                't.ticket_instance_id as id',
                'tp.name as title',
                DB::raw('NULL as seatNumber'),
                DB::raw("COALESCE(t.status, 'active') as status"),
                DB::raw("COALESCE(tc.category_name, 'Food & Beverage') as category_name"),
                't.qr_code as qr_value',
                't.valid_until as valid_until',
                'ord.order_date as order_date'
            )
            ->get();

        $combined = $tickets->concat($foodOrders)->values();

        return response()->json($combined);
    }

    public function redeem(Request $request)
    {
        $userId = $request->session()->get('user_id') ?? $request->cookie('user_id');
        if (!$userId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $qrCode = $request->input('qr_code');
        if (!$qrCode) {
            return response()->json(['error' => 'QR code is required'], 400);
        }

        // Update ticket instance status to 'used'
        $updated = DB::table('ticket_instances')
            ->where('qr_code', $qrCode)
            ->update(['status' => 'used']);

        if ($updated) {
            return response()->json(['success' => true, 'message' => 'Ticket redeemed successfully']);
        }

        return response()->json(['error' => 'Ticket not found or already used'], 404);
    }
}
