<?php

namespace App\Http\Controllers;

use App\Models\TicketProduct;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        return TicketProduct::with(['poster', 'category'])
            ->where('category_id', 1) // Movie
            ->get()
            ->map(function ($movie) {
                return [
                    'product_id' => $movie->product_id,
                    'name'       => $movie->name,
                    'poster_url' => $movie->poster
                        ? asset('storage/' . $movie->poster->media_path)
                        : asset('images/posters/default.jpg'),
                ];
            });
    }
}

