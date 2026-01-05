<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    function index(){
        return null;
    }

    function cart(Request $request){
        $cart = $request->input('cart');
        return response()->json(['cart' => $cart]);
    }

    function orders(){
        $user = auth()->user();
        $orders = DB::table('orders')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['orders' => $orders]);
    }
}
