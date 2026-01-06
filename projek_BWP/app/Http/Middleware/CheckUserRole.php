<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckUserRole
{
    /**
     * Handle an incoming request to ensure user is NOT an admin (regular user only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // User must be authenticated (should be checked by auth middleware already)
        if (!session()->has('user_id')) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated'
                ], 401);
            }
            return redirect()->route('login');
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
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'User not found'
                    ], 401);
                }
                return redirect()->route('login');
            }

            $userRole = $user->role;
            session(['user_role' => $userRole]);
        }

        // Only allow regular users, deny admin access
        if ($userRole === 'admin') {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Admins cannot access user routes'
                ], 403);
            }
            // Redirect admin to admin panel
            return redirect('/admin');
        }

        return $next($request);
    }
}
