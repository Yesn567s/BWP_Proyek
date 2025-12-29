<?php

namespace App\Http\Controllers;

use App\Models\TicketInstance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketInstanceController extends Controller
{
    public function index(Request $request)
    {
        $tickets = DB::table('ticket_instances as t')
            ->join('order_items as o', 't.order_item_id', '=', 'o.order_item_id')
            ->join('ticket_products as tp', 'o.product_id', '=', 'tp.product_id')
            ->join('ticket_categories as tc', 'tp.category_id', '=', 'tc.category_id')
            ->join('schedules as s', 'o.schedule_id', '=', 's.schedule_id')
            ->join('seats', 'o.seat_id', '=', 'seats.seat_id')
            ->select(
                't.ticket_instance_id as id',
                'tp.name as title',
                'seats.seat_number as seatNumber',
                't.status as status',
                'tc.category_name as category_name',
                't.qr_code as qr_value',
                's.start_datetime as date'
            )
            // ->where('ti.user_id', $request->session()->get('user_id'))
            ->get();
        return response()->json($tickets);
    }
}
