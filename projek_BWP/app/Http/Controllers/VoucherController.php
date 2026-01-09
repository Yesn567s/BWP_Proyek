<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    public function index()
    {
        return response()->json(
            Voucher::where('is_active', 1)->get()
        );
    }

    /**
     * Admin: Get all vouchers (including inactive)
     */
    public function adminIndex()
    {
        return response()->json(
            Voucher::orderBy('created_at', 'desc')->get()
        );
    }

    /**
     * Admin: Create a new voucher
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_usage' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
            'media' => 'nullable|image|max:2048',
            'media_path' => 'nullable|string|max:200',
        ]);

        $mediaPath = $validated['media_path'] ?? '';

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $dir = public_path('uploads/vouchers');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $mediaPath = 'uploads/vouchers/' . $filename;
        }

        $voucher = Voucher::create([
            'code' => strtoupper($validated['code']),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'max_usage' => $validated['max_usage'] ?? null,
            'is_active' => $validated['is_active'],
            'media' => $mediaPath,
            'used_count' => 0,
        ]);

        return response()->json($voucher, 201);
    }

    /**
     * Admin: Update a voucher
     */
    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code,' . $id . ',voucher_id',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_usage' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
            'media' => 'nullable|image|max:2048',
            'media_path' => 'nullable|string|max:200',
        ]);

        $mediaPath = $validated['media_path'] ?? $voucher->media ?? '';

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $dir = public_path('uploads/vouchers');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $mediaPath = 'uploads/vouchers/' . $filename;

            // Delete old file if it existed in the uploads directory
            if (!empty($voucher->media)) {
                $oldPath = public_path($voucher->media);
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }

        $voucher->update([
            'code' => strtoupper($validated['code']),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'max_usage' => $validated['max_usage'] ?? null,
            'is_active' => $validated['is_active'],
            'media' => $mediaPath,
        ]);

        return response()->json($voucher);
    }

    /**
     * Admin: Delete a voucher
     */
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        
        // Also delete related user_vouchers and voucher_usages
        DB::table('user_vouchers')->where('voucher_id', $id)->delete();
        DB::table('voucher_usages')->where('voucher_id', $id)->delete();
        
        $voucher->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Get user vouchers with remaining usage
     */
    public function getUserVouchers(Request $request)
    {
        // Check session-based authentication
        if (!session()->has('user_id')) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $userId = session('user_id');

        $userVouchers = DB::table('user_vouchers')
            ->join('vouchers', 'user_vouchers.voucher_id', '=', 'vouchers.voucher_id')
            ->where('user_vouchers.user_id', $userId)
            ->select(
                'vouchers.voucher_id',
                'vouchers.code',
                'vouchers.title',
                'vouchers.description',
                'vouchers.end_date',
                'vouchers.is_active',
                'user_vouchers.usage_count',
                'user_vouchers.max_usage_per_user'
            )
            ->get()
            ->map(function ($voucher) {
                return [
                    'id' => $voucher->voucher_id,
                    'code' => $voucher->code,
                    'title' => $voucher->title,
                    'description' => $voucher->description,
                    'end_date' => $voucher->end_date,
                    'is_active' => (bool)$voucher->is_active,
                    'usage_count' => $voucher->usage_count,
                    'max_usage_per_user' => $voucher->max_usage_per_user,
                    'remaining_use' => $voucher->max_usage_per_user !== null 
                        ? max(0, $voucher->max_usage_per_user - $voucher->usage_count)
                        : null,
                    'is_expired' => now()->isAfter($voucher->end_date),
                    'used' => $voucher->usage_count > 0 && $voucher->max_usage_per_user !== null && $voucher->usage_count >= $voucher->max_usage_per_user
                ];
            });

        return response()->json($userVouchers);
    }

    /**
     * Retrieve a single active voucher by its code.
     */
    public function showByCode(string $code)
    {
        $voucher = Voucher::where('code', $code)
            ->where('is_active', 1)
            ->first();

        if (!$voucher) {
            return response()->json(['error' => 'Invalid voucher code'], 404);
        }

        if ($voucher->end_date && now()->isAfter($voucher->end_date)) {
            return response()->json(['error' => 'Voucher expired'], 400);
        }

        return response()->json($voucher);
    }
}
