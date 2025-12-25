<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            DB::table('users')->insert([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]);

            return response('success', 200);

        } catch (\Illuminate\Database\QueryException $e) {

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

        if (!$user || $user->password !== $request->password) {
            return response('invalid', 401);
        }

        session([
            'user_id'   => $user->user_id,
            'user_name' => $user->name,
            'user_role' => $user->role,
        ]);

        // 👇 IMPORTANT: return role info
        return response()->json([
            'status' => 'success',
            'role' => $user->role
        ]);
    }


    
}
