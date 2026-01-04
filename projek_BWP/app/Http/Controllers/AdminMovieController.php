<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminMovieController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:100',
            'genre'            => 'required|string|max:200',
            'description'      => 'required|string|max:1000',
            'releaseDate'      => 'required|string',
            'playingTime'      => 'required|integer|min:1|max:30',
            'duration_minutes' => 'required|integer|min:60|max:240',
            'ageRating'        => 'required|in:SU,13+,17+',
            'price'            => 'required|numeric|min:1000|max:100000000',
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

            $insertedSchedules = $this->generateSchedules(
                $productId,
                $request->playingTime,
                $request->duration_minutes
            );

            if ($insertedSchedules === 0) {
                DB::rollBack();

                return response()->json([
                    'type'    => 'studio_full',
                    'message' => 'All studios are fully booked. Please create a new studio.'
                ], 409);
            }


        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'Failed to create movie',
                'detail' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, int $productId)
    {
        $request->validate([
            'title'            => 'required|string|max:100',
            'genre'            => 'required|string|max:200',
            'description'      => 'required|string|max:1000',
            'duration_minutes' => 'required|integer|min:60|max:240',
            'ageRating'        => 'required|in:SU,13+,17+',
            'price'            => 'required|numeric|min:1000|max:100000000',
            'poster'           => 'nullable|image|mimes:jpg,jpeg,png'
        ]);

        DB::beginTransaction();

        try {
            $movie = DB::table('ticket_products')
                ->where('product_id', $productId)
                ->where('category_id', 1)
                ->first();

            if (!$movie) {
                DB::rollBack();

                return response()->json([
                    'error' => 'Movie not found or not a category 1 product'
                ], 404);
            }

            // Run the update even if values are unchanged so a no-op still counts as success
            DB::table('ticket_products')
                ->where('product_id', $productId)
                ->update([
                    'name'             => $request->title,
                    'description'      => $request->description,
                    'genre'            => $request->genre,
                    'base_price'       => $request->price,
                    'duration_minutes' => $request->duration_minutes,
                    'age_rating'       => $request->ageRating,
                ]);

            if ($request->hasFile('poster')) {
                $path = $request->file('poster')->store('posters', 'public');

                $existing = DB::table('product_media')
                    ->where('product_id', $productId)
                    ->where('media_type', 'poster')
                    ->first();

                if ($existing) {
                    DB::table('product_media')
                        ->where('id', $existing->id)
                        ->update(['media_url' => $path]);
                } else {
                    DB::table('product_media')->insert([
                        'product_id' => $productId,
                        'media_type' => 'poster',
                        'media_url'  => $path,
                    ]);
                }
            }

            DB::commit();

            return response()->json(['message' => 'Movie updated']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'Failed to update movie',
                'detail' => $e->getMessage()
            ], 500);
        }
    }
    private function generateSchedules($productId, $days, $duration)
    {
        $days = (int) $days;
        $duration = (int) $duration;

        $cinemaVenueIds = [1,6,7,8,9,10,11,12,13,14];

        $studios = DB::table('studios')
            ->whereIn('venue_id', $cinemaVenueIds)
            ->pluck('studio_id');

        $startDate = \Carbon\Carbon::now()->startOfDay()->addDays(1);

        $inserted = 0;

        foreach ($studios as $studioId) {
            foreach (range(0, $days - 1) as $day) {

                $time = $startDate
                    ->copy()
                    ->addDays($day)
                    ->setTime(10, 0);

                while ($time->hour < 22) {

                    $start = $time->copy();
                    $end   = $time->copy()->addMinutes($duration);

                    $isOccupied = DB::table('schedules')
                        ->where('studio_id', $studioId)
                        ->where(function ($q) use ($start, $end) {
                            $q->where('start_datetime', '<', $end)
                            ->where('end_datetime',   '>', $start);
                        })
                        ->exists();

                    if (!$isOccupied) {
                        DB::table('schedules')->insert([
                            'product_id'     => $productId,
                            'studio_id'      => $studioId,
                            'start_datetime' => $start,
                            'end_datetime'   => $end,
                        ]);
                        $inserted++;
                    }

                    $time->addMinutes($duration + 20);
                }
            }
        }

        return $inserted;
    }

}

