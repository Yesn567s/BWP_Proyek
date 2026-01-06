<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckRole
{
    /**
     * Handle an incoming request to check user role (admin or user).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role - The required role (admin or user)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        // First check if user is authenticated
        if (!session()->has('user_id')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated - Please login first'
            ], 401);
        }

        // Get user role from session or database
        $userRole = session('user_role');

        // If no role in session, fetch from database
        if (!$userRole) {
            $user = DB::table('users')
                ->where('user_id', session('user_id'))
                ->select('role')
                ->first();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 401);
            }

            $userRole = $user->role;
            session(['user_role' => $userRole]);
        }

        // If specific role is required, check it
        if ($role && $userRole !== $role) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized - You do not have permission to access this resource',
                'required_role' => $role,
                'your_role' => $userRole
            ], 403);
        }

        return $next($request);
    }
}
