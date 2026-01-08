<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Insert user
            $userId = DB::table('users')->insertGetId([
                'name'              => $request->name,
                'email'             => $request->email,
                'password'          => $request->password, // ⚠ plain for now (make sure to hash in production)
                'role'              => 'user',
                'profile_picture'   => null,
                'two_factor_auth'   => 0,
                'language_code'     => 'en',
                'member_start'      => now(),
                'phone_number'      => $request->phone,
                'points'            => 0,
            ]);

            // 2️⃣ Insert default notification settings
            DB::table('user_notification_setting')->insert([
                [
                    'user_id' => $userId,
                    'notification_type_id' => 1,
                    'email_enabled' => 1,
                    'push_enabled' => 0,
                ],
                [
                    'user_id' => $userId,
                    'notification_type_id' => 2,
                    'email_enabled' => 1,
                    'push_enabled' => 0,
                ],
                [
                    'user_id' => $userId,
                    'notification_type_id' => 3,
                    'email_enabled' => 1,
                    'push_enabled' => 0,
                ],
                [
                    'user_id' => $userId,
                    'notification_type_id' => 4,
                    'email_enabled' => 1,
                    'push_enabled' => 1,
                ],
            ]);

            // 3️⃣ Assign 2 random vouchers to new user
            $allVouchers = DB::table('vouchers')
                ->where('is_active', 1)
                ->pluck('voucher_id')
                ->toArray();

            if (count($allVouchers) >= 2) {
                // Get 2 random vouchers
                $randomVouchers = array_slice(
                    $allVouchers,
                    0,
                    min(2, count($allVouchers)),
                    true
                );
                shuffle($randomVouchers);
                $randomVouchers = array_slice($randomVouchers, 0, 2);

                $voucherAssignments = [];
                foreach ($randomVouchers as $voucherId) {
                    $voucherAssignments[] = [
                        'user_id' => $userId,
                        'voucher_id' => $voucherId,
                        'usage_count' => 0,
                        'max_usage_per_user' => rand(1, 10),
                        'assigned_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                DB::table('user_vouchers')->insert($voucherAssignments);
            }

            DB::commit();
            return response('success', 200);

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            // Duplicate email
            if ($e->errorInfo[1] == 1062) {
                return response('email_exists', 409);
            }

            return response('error', 500);
        }
    }

    // public function login(Request $request)
    // {
    //     $user = DB::table('users')
    //         ->where('email', $request->email)
    //         ->where('password', $request->password)
    //         ->first();

    //     if ($user) {
    //         // Optional: store user session
    //         session(['user_id' => $user->user_id]);
    //         session(['user_name' => $user->name]);

    //         return response('success', 200);
    //     }

    //     return response('invalid', 401);
    // }

    public function login(Request $request)
    {
        $user = DB::table('users')
            ->where('email', $request->email)
            ->first();

        // Invalid credentials
        if (!$user || $user->password !== $request->password) {
            return response()->json([
                'status' => 'error'
            ], 401);
        }

        // Safeguard: Clear conflicting sessions based on user role
        if ($user->role === 'admin') {
            // Admin logging in - clear any user sessions
            session()->forget(['user_id', 'user_name']);
        } else {
            // Regular user logging in - clear any admin sessions
            session()->forget(['user_id', 'user_name']);
        }

        // Store session
        session([
            'user_id'   => $user->user_id,
            'user_name' => $user->name,
            'user_role' => $user->role,
        ]);

        // Return role for frontend redirect
        return response()->json([
            'status'    => 'success',
            'user_id'   => $user->user_id,
            'user_name' => $user->name,
            'role'      => $user->role,
            'language_code' => $user->language_code,
            'session' => session('user_id'),
        ]);
    }



    
}
