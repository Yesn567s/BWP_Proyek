<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminTixFunController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:100',
            'category_id'       => 'required|exists:ticket_categories,category_id',
            'description'       => 'required|string|max:2000',
            'base_price'        => 'required|numeric|min:0',
            'duration_minutes'  => 'nullable|integer|min:1',
            'age_rating'        => 'nullable|string|max:10',
            'genre'             => 'nullable|string|max:200',
            'requires_schedule' => 'required',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        DB::beginTransaction();

        try {
            $productId = DB::table('ticket_products')->insertGetId([
                'category_id'       => $request->category_id,
                'name'              => $request->name,
                'description'       => $request->description,
                'base_price'        => $request->base_price,
                'duration_minutes'  => $request->duration_minutes,
                'age_rating'        => $request->age_rating,
                'genre'             => $request->genre,
                'requires_schedule' => $request->requires_schedule ? 1 : 0,
                'requires_seat'     => 0,
                'rating'            => null,
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('posters', 'public');

                DB::table('product_media')->insert([
                    'product_id' => $productId,
                    'media_type' => 'image',
                    'media_url'  => $path,
                ]);
            }

            DB::commit();

            return response()->json([
                'success'    => true,
                'message'    => 'TixFun created successfully',
                'product_id' => $productId,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create TixFun',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
