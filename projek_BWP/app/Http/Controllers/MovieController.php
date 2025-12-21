<?php

namespace App\Http\Controllers;

use App\Models\TicketProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index()
    {
        return TicketProduct::with('poster')
            ->where('category_id', 1)
            ->get()
            ->map(function ($movie) {
                return [
                    'id'    => $movie->product_id,
                    'title' => $movie->name,
                    'poster'=> $movie->poster
                        ? Storage::url($movie->poster->media_url)
                        : asset('images/posters/default.jpg'),
                ];
            });
    }

}

