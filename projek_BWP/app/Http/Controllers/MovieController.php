<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\TicketProduct;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index()
    {
        return TicketProduct::with('poster')
            ->where('category_id', 1)
            ->get()
            ->map(fn ($movie) => $this->formatMovie($movie));
    }

    public function nowPlaying()
{
    return TicketProduct::with('poster')
        ->where('category_id', 1) // movie only
        ->whereHas('schedules', function ($q) {
            $q->where('start_datetime', '<=', now())
              ->where('end_datetime', '>=', now());
        })
        ->get()
        ->map(fn ($movie) => $this->formatMovie($movie));
}


    public function coomingSoon()
{
    return TicketProduct::with('poster')
        ->where('category_id', 1)
        ->whereHas('schedules', function ($q) {
            $q->where('start_datetime', '>', now());
        })
        ->get()
        ->map(fn ($movie) => $this->formatMovie($movie));
}


    private function formatMovie($movie)
    {
        return [
            'id'     => $movie->product_id,
            'title'  => $movie->name,
            'desc'   => $movie->description,
            'price'  => $movie->base_price,
            'rating' => $movie->rating,
            'poster' => $movie->poster
                ? Storage::url($movie->poster->media_url)
                : asset('images/posters/default.jpg'),
        ];
    }

    public function trailers()
    {
        $movies = TicketProduct::with(['media' => function ($q) {
            $q->where('media_type', 'trailer');
        }])->whereHas('media', function ($q) {
            $q->where('media_type', 'trailer');
        })->get();

        return $movies->map(function ($movie) {
            $media = $movie->media->first();

            return [
                'id' => $movie->product_id,
                'title' => $movie->name,
                'description' => $movie->description,
                'youtube_url' => $media ? $media->media_url : null,
            ];
        });
    }

    // public function dates($productId)
    // {
    //     $dates = Schedule::where('product_id', $productId)
    //         ->selectRaw('DATE(start_datetime) as value')
    //         ->distinct()
    //         ->orderBy('value')
    //         ->get()
    //         ->map(function ($item) {
    //             $date = \Carbon\Carbon::parse($item->value);
    //             return [
    //                 'value'   => $date->toDateString(), // 2025-12-02
    //                 'weekday'=> $date->translatedFormat('D'),
    //                 'day'    => $date->format('d'),
    //                 'month'  => $date->translatedFormat('M'),
    //             ];
    //         });

    //     return response()->json($dates);
    // }



}

