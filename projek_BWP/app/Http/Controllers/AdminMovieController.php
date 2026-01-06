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
        'poster'           => 'required|image|mimes:jpg,jpeg,png',

        // ✅ NEW
        'venue_id'         => 'required|exists:venues,venue_id',
        'studio_ids'       => 'required|array|min:1',
        'studio_ids.*'     => 'exists:studios,studio_id',
    ]);

    DB::beginTransaction();

    try {
        // 1️⃣ Insert movie product
        $productId = DB::table('ticket_products')->insertGetId([
            'category_id'       => 1, // Movie
            'name'              => $request->title,
            'description'       => $request->description,
            'genre'             => $request->genre,
            'base_price'        => $request->price,
            'requires_schedule' => 1,
            'requires_seat'     => 1,
            'duration_minutes'  => $request->duration_minutes,
            'age_rating'        => $request->ageRating,
        ]);

        // 2️⃣ Store poster
        $path = $request->file('poster')->store('posters', 'public');

        DB::table('product_media')->insert([
            'product_id' => $productId,
            'media_type' => 'poster',
            'media_url'  => $path,
        ]);

        // 3️⃣ Generate schedules ONLY for selected studios
        $insertedSchedules = $this->generateSchedules(
            $productId,
            $request->studio_ids,
            $request->playingTime,
            $request->duration_minutes
        );

        if ($insertedSchedules === 0) {
            DB::rollBack();

            return response()->json([
                'type'    => 'studio_full',
                'message' => 'All selected studios are fully booked.'
            ], 409);
        }

        // ✅ IMPORTANT
        DB::commit();

        // ✅ SUCCESS RESPONSE (THIS WAS MISSING)
        return response()->json([
            'message'    => 'Movie created successfully',
            'product_id' => $productId
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'error'  => 'Failed to create movie',
            'detail' => $e->getMessage()
        ], 500);
    }
}

    private function generateSchedules($productId, $studioIds, $days, $duration)
{
    $days     = (int) $days;
    $duration = (int) $duration;

    $startDate = Carbon::now()->startOfDay()->addDays(1);
    $inserted = 0;

    foreach ($studioIds as $studioId) {
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

