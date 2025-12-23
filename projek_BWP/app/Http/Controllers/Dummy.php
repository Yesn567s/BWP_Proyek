<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Dummy extends Controller
{
    function index(){
        $users = DB::table('users')->get();

        $stats = [
            'totalProducts' => DB::table('ticket_products')->count(),
            'totalVenues'   => DB::table('venues')->count(),
            'totalOrders'   => DB::table('orders')->count(),
            'totalUsers'    => DB::table('users')->count(),
        ];

        return view('dummy', [
            'users' => $users,
            'stats' => $stats
        ]);
    }
}
