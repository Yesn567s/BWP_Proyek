<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    public function index()
    {
        return response()->json(
            Voucher::where('is_active', 1)->get()
        );
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
}
