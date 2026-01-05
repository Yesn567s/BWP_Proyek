<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use App\Models\Venue;
use Illuminate\Http\Request;

class StudioController extends Controller
{
    public function store(Request $request, Venue $venue)
    {
        $validated = $request->validate([
            'studio_name' => 'required|string|max:50',
            // studio_type optional (DB default = Regular)
        ]);

        $studio = Studio::create([
            'venue_id'    => $venue->venue_id,
            'studio_name' => $validated['studio_name'],
            'studio_type' => 'Regular',
        ]);

        return response()->json($studio, 201);
    }
}
