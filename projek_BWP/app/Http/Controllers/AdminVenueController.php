<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class AdminVenueController extends Controller
{
    public function venuesWithStudios()
    {
        return Venue::with('studios')->get()->map(function ($venue) {
            return [
                'id' => $venue->venue_id,
                'name' => $venue->venue_name,
                'venue_type' => $venue->venue_type,
                'location' => $venue->location,
                'studios' => $venue->studios->map(function ($studio) {
                    return [
                        'id' => $studio->studio_id,
                        'name' => $studio->studio_name,
                    ];
                })->values(),
            ];
        })->values();
    }
}
