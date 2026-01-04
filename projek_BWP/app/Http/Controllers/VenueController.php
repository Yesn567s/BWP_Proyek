<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function foodVenues()
    {
        return response()->json(
            Venue::select(
                'venue_id',
                'venue_name',
                'venue_type',
                'location'
            )->where('venue_type', 'Food & Beverage')
            ->get()
        );
    }

    public function partners()
    {
        return response()->json(
            Venue::select(
                'venue_id',
                'venue_name',
                'venue_type',
                'location'
            )
                ->where('venue_type', 'Cinema')
                ->get()
        );
    }

    public function show($id)
    {
        return response()->json(
            Venue::with('studios.schedules')
                ->where('venue_id', $id)
                ->firstOrFail()
        );
    }

}
