<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminFoodController extends Controller
{
    public function show(int $productId)
    {
        $food = DB::table('ticket_products as tp')
            ->leftJoin('product_media as pm', function ($join) {
                $join->on('pm.product_id', '=', 'tp.product_id')
                    ->where('pm.media_type', 'poster');
            })
            ->where('tp.product_id', $productId)
            ->where('tp.category_id', 8)
            ->select(
                'tp.product_id as id',
                'tp.name as title',
                'tp.base_price as price',
                'tp.description',
                'pm.media_url'
            )
            ->first();

        if (!$food) {
            return response()->json(['error' => 'Food not found'], 404);
        }

        return response()->json([
            'id'          => $food->id,
            'title'       => $food->title,
            'price'       => $food->price,
            'description' => $food->description,
            'imageUrl'    => $food->media_url ? asset('storage/' . $food->media_url) : null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'price'       => 'required|integer|min:1000|max:100000000',
            'poster'      => 'required|image|mimes:jpg,jpeg,png',
        ]);

        DB::beginTransaction();

        try {
            $productId = DB::table('ticket_products')->insertGetId([
                'category_id'       => 8,
                'name'              => $request->title,
                'description'       => $request->description,
                'base_price'        => $request->price,
                'requires_schedule' => 0,
                'requires_seat'     => 0,
                'duration_minutes'  => null,
                'age_rating'        => null,
            ]);

            $path = $request->file('poster')->store('food', 'public');

            DB::table('product_media')->insert([
                'product_id' => $productId,
                'media_type' => 'poster',
                'media_url'  => $path,
            ]);

            DB::commit();

            return response()->json([
                'message'    => 'Food item created successfully',
                'product_id' => $productId,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error'  => 'Failed to create food item',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, int $productId)
    {
        $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'price'       => 'required|integer|min:1000|max:100000000',
            'poster'      => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        DB::beginTransaction();

        try {
            $exists = DB::table('ticket_products')
                ->where('product_id', $productId)
                ->where('category_id', 8)
                ->exists();

            if (!$exists) {
                DB::rollBack();
                return response()->json(['error' => 'Food not found'], 404);
            }

            DB::table('ticket_products')
                ->where('product_id', $productId)
                ->update([
                    'name'        => $request->title,
                    'description' => $request->description,
                    'base_price'  => $request->price,
                ]);

            if ($request->hasFile('poster')) {
                $path = $request->file('poster')->store('food', 'public');

                DB::table('product_media')->updateOrInsert(
                    [
                        'product_id' => $productId,
                        'media_type' => 'poster',
                    ],
                    [
                        'media_url' => $path,
                    ]
                );
            }

            DB::commit();

            return response()->json(['message' => 'Food item updated']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error'  => 'Failed to update food item',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }
}
