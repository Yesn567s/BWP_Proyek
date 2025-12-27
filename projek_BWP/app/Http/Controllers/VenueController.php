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
            )->get()
        );
    }
}
