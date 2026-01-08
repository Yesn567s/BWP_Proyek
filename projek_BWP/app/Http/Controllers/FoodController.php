<?php

namespace App\Http\Controllers;

use App\Models\TicketProduct;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function allFood()
    {
        $foods = TicketProduct::where('category_id', 8)
            ->with(['poster'])
            ->get()
            ->map(function ($food) {
                return [
                    'id'    => $food->product_id,
                    'title' => $food->name,
                    'price' => $food->base_price,
                    'imageUrl' => $food->poster
                        ? asset('storage/' . $food->poster->media_url)
                        : null
                ];
            });

        return response()->json($foods);
    }

    public function show(int $productId)
    {
        $food = TicketProduct::with('poster')
            ->where('category_id', 8)
            ->whereKey($productId)
            ->first();

        if (!$food) {
            return response()->json(['error' => 'Food not found'], 404);
        }

        return response()->json([
            'id'          => $food->product_id,
            'title'       => $food->name,
            'price'       => $food->base_price,
            'description' => $food->description,
            'category'    => 'Food & Beverage',
            'imageUrl'    => $food->poster
                ? asset('storage/' . $food->poster->media_url)
                : null,
        ]);
    }
}
