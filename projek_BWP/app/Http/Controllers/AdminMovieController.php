<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminMovieController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:100',
            'genre'            => 'required|string|max:200',
            'description'      => 'required|string|max:255',
            'releaseDate'      => 'required|string',
            'playingTime'      => 'required|integer|min:1|max:30',
            'duration_minutes' => 'required|integer|min:60|max:240',
            'ageRating'        => 'required|in:SU,13+,17+',
            'price'            => 'required|integer|min:10000|max:100000',
            'poster'           => 'required|image|mimes:jpg,jpeg,png'
        ]);

        DB::beginTransaction();

        try {
            /* 1️⃣ Insert movie product */
            $productId = DB::table('ticket_products')->insertGetId([
                'category_id'       => 1, // Movie
                'name'              => $request->title,
                'description'       => $request->description,
                'genre'             => $request->genre,
                'base_price'        => $request->price,
                'requires_schedule' => 1,
                'requires_seat'     => 1,
                'duration_minutes'  => $request->duration_minutes,
                'age_rating'        => $request->ageRating
            ]);

            /* 2️⃣ Store poster */
            $path = $request->file('poster')->store('posters', 'public');

            DB::table('product_media')->insert([
                'product_id' => $productId,
                'media_type' => 'poster',
                'media_url'  => $path
            ]);

            /* 3️⃣ Generate schedules */
            $this->generateSchedules(
                $productId,
                (int) $request->playingTime,
                (int) $request->duration_minutes
            );


            DB::commit();

            return response()->json([
                'message' => 'Movie created successfully'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'Failed to create movie',
                'detail' => $e->getMessage()
            ], 500);
        }
    }
    private function generateSchedules($productId, $days, $duration)
    {
        $studios = DB::table('studios')->pluck('studio_id');
        $startDate = Carbon::now()->startOfDay()->addDays(1);

        foreach (range(0, $days - 1) as $day) {
            foreach ($studios as $studioId) {
                $time = $startDate->copy()->addDays($day)->setTime(10, 0);

                while ($time->hour < 22) {
                    DB::table('schedules')->insert([
                        'product_id'    => $productId,
                        'studio_id'     => $studioId,
                        'start_datetime'=> $time,
                        'end_datetime'  => $time->copy()->addMinutes($duration)
                    ]);

                    // 20 min cleaning buffer
                    $time->addMinutes($duration + 20);
                }
            }
        }
    }
}

