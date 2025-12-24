<?php

namespace App\Http\Controllers;

use App\Models\TicketProduct;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ScheduleController extends Controller
{
    public function show(int $id): JsonResponse
    {
        $movie = TicketProduct::with(['category', 'poster'])->findOrFail($id);

        $movieDto = [
            'id'       => $movie->product_id,
            'title'    => $movie->name,
            'desc'     => $movie->description,
            'category' => $movie->category?->category_name,
            'price'    => $movie->base_price,
            'poster'   => $movie->poster
                ? Storage::url($movie->poster->media_url)
                : asset('images/posters/default.jpg'),
        ];

        $rows = DB::table('schedules as s')
            ->leftJoin('studios as st', 's.studio_id', '=', 'st.studio_id')
            ->leftJoin('venues as v', 'st.venue_id', '=', 'v.venue_id')
            ->select(
                's.schedule_id',
                's.start_datetime',
                's.end_datetime',
                'st.studio_id',
                'st.studio_name',
                'v.venue_id',
                'v.venue_name',
                'v.location'
            )
            ->where('s.product_id', $id)
            ->orderBy('s.start_datetime')
            ->get();

        $schedules = $rows->map(function ($row) {
            $start = Carbon::parse($row->start_datetime);
            $end   = Carbon::parse($row->end_datetime);

            return [
                'id'          => $row->schedule_id,
                'date'        => $start->toDateString(),
                'start'       => $start->toDateTimeString(),
                'end'         => $end->toDateTimeString(),
                'start_time'  => $start->format('H:i'),
                'end_time'    => $end->format('H:i'),
                'venue_id'    => $row->venue_id,
                'venue_name'  => $row->venue_name,
                'location'    => $row->location,
                'studio_name' => $row->studio_name,
            ];
        });

        return response()->json([
            'movie'     => $movieDto,
            'schedules' => $schedules,
        ]);
    }
}
