<?php

namespace App\Http\Controllers;

use App\Models\TicketProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketProductController extends Controller
{
    // public function index()
    // {
    //     $tickets = TicketProduct::with([
    //         'category',
    //         'poster',
    //         'schedules.venue'
    //     ])->get()->map(function ($item) {
    //         return [
    //             'id'       => $item->product_id,
    //             'title'    => $item->name,
    //             'category' => $item->category?->category_name,
    //             'price'    => $item->base_price,
    //             'location' => optional(
    //                 $item->schedules->first()?->venue
    //             )->location,
    //             'imageUrl' => $item->poster
    //                 ? '/storage/' . $item->poster->media_url
    //                 : null
    //         ];
    //     });

    //     return response()->json($tickets);
    // }

    public function index(Request $request)
    {
        $tickets = DB::table('ticket_products as tp')
            ->join('ticket_categories as tc', 'tp.category_id', '=', 'tc.category_id')
            ->leftJoin('product_media as pm', function ($join) {
                $join->on('pm.product_id', '=', 'tp.product_id')
                    ->where('pm.media_type', 'poster');
            })
            ->select(
                'tp.product_id as id',
                'tp.name as title',
                'tc.category_name as category',
                'tp.category_id as category_id', // 👈 important
                'tp.base_price as price',
                DB::raw("CONCAT('/storage/', pm.media_url) as imageUrl")
            )
            ->when($request->category, function ($query) use ($request) {
                $query->where('tp.category_id', $request->category);
            })
            ->get();

        return response()->json($tickets);
    }


}
