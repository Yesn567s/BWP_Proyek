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
        ]);
    }



    
}
