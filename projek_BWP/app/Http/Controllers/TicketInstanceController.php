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
                's.start_datetime as date'
            )
            ->get();

        // Food & Beverage orders (no ticket instances)
        $foodOrders = DB::table('order_items as oi')
            ->join('orders as ord', 'oi.order_id', '=', 'ord.order_id')
            ->join('ticket_products as tp', 'oi.product_id', '=', 'tp.product_id')
            ->leftJoin('ticket_categories as tc', 'tp.category_id', '=', 'tc.category_id')
            ->where('ord.user_id', $userId)
            ->where('tp.category_id', 8) // category 8 = Food & Beverage
            ->groupBy('ord.order_id', 'tp.product_id', 'tp.name', 'tc.category_name', 'ord.order_date')
            ->select(
                DB::raw('CONCAT(ord.order_id, "-", tp.product_id) as id'),
                'tp.name as title',
                DB::raw('NULL as seatNumber'),
                DB::raw("'completed' as status"),
                DB::raw("COALESCE(tc.category_name, 'Food & Beverage') as category_name"),
                DB::raw('ord.order_id as qr_value'),
                'ord.order_date as date',
                DB::raw('COUNT(*) as quantity')
            )
            ->get();

        $combined = $tickets->concat($foodOrders)->values();

        return response()->json($combined);
    }
}
