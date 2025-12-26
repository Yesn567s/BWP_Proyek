<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminMovieController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:100',
            'genre'        => 'required|string',
            'releaseDate'  => 'required|string',
            'poster'       => 'required|image|mimes:jpg,jpeg,png',
        ]);

        DB::beginTransaction();

        try {
            // 1️⃣ Insert movie
            $productId = DB::table('ticket_products')->insertGetId([
                'category_id'       => 1, // Movie
                'name'              => $request->title,
                'description'       => $request->genre . ' | Released: ' . $request->releaseDate,
                'base_price'        => 45000, // TEMP (you can add input later)
                'requires_schedule' => 1,
                'requires_seat'     => 1,
            ]);

            // 2️⃣ Store poster
            $path = $request->file('poster')->store('posters', 'public');

            DB::table('product_media')->insert([
                'product_id' => $productId,
                'media_type' => 'poster',
                'media_url'  => $path,
            ]);

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
}
