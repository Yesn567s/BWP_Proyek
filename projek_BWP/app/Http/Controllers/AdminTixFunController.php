<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminTixFunController extends Controller
{
    public function show(int $productId)
    {
        $product = DB::table('ticket_products as tp')
            ->leftJoin('ticket_categories as tc', 'tc.category_id', '=', 'tp.category_id')
            ->leftJoin('product_media as pm', function ($join) {
                $join->on('pm.product_id', '=', 'tp.product_id')
                    ->where('pm.media_type', 'image');
            })
            ->where('tp.product_id', $productId)
            ->select(
                'tp.product_id as id',
                'tp.category_id',
                'tc.category_name',
                'tp.name',
                'tp.description',
                'tp.base_price',
                'tp.duration_minutes',
                'tp.age_rating',
                'tp.genre',
                'tp.requires_schedule',
                'pm.media_url'
            )
            ->first();

        if (!$product) {
            return response()->json(['error' => 'TixFun not found'], 404);
        }

        return response()->json([
            'id'                => $product->id,
            'category_id'       => $product->category_id,
            'category_name'     => $product->category_name,
            'name'              => $product->name,
            'description'       => $product->description,
            'base_price'        => $product->base_price,
            'duration_minutes'  => $product->duration_minutes,
            'age_rating'        => $product->age_rating,
            'genre'             => $product->genre,
            'requires_schedule' => (bool) $product->requires_schedule,
            'imageUrl'          => $product->media_url ? asset('storage/' . $product->media_url) : null,
        ]);
    }

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

    public function update(Request $request, int $productId)
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
            $exists = DB::table('ticket_products')
                ->where('product_id', $productId)
                ->exists();

            if (!$exists) {
                DB::rollBack();
                return response()->json(['error' => 'TixFun not found'], 404);
            }

            DB::table('ticket_products')
                ->where('product_id', $productId)
                ->update([
                    'category_id'       => $request->category_id,
                    'name'              => $request->name,
                    'description'       => $request->description,
                    'base_price'        => $request->base_price,
                    'duration_minutes'  => $request->duration_minutes,
                    'age_rating'        => $request->age_rating,
                    'genre'             => $request->genre,
                    'requires_schedule' => $request->requires_schedule ? 1 : 0,
                ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('posters', 'public');

                DB::table('product_media')->updateOrInsert(
                    [
                        'product_id' => $productId,
                        'media_type' => 'image',
                    ],
                    [
                        'media_url' => $path,
                    ]
                );
            }

            DB::commit();

            return response()->json(['message' => 'TixFun updated successfully']);

        } catch (
            \Exception $e
        ) {
            DB::rollBack();

            return response()->json([
                'error'  => 'Failed to update TixFun',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(int $productId)
    {
        DB::beginTransaction();

        try {
            DB::table('product_media')
                ->where('product_id', $productId)
                ->where('media_type', 'image')
                ->delete();

            $deleted = DB::table('ticket_products')
                ->where('product_id', $productId)
                ->delete();

            if (!$deleted) {
                DB::rollBack();
                return response()->json(['error' => 'TixFun not found'], 404);
            }

            DB::commit();

            return response()->json(['message' => 'TixFun deleted successfully']);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error'  => 'Failed to delete TixFun',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }
}
