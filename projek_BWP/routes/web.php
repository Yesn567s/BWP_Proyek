<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dummy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

// Route::get('/', function () {
//     return view('dummy');
// });

Route::get('/', [Dummy::class, "index"])->name("index");

Route::get('/login', function () {
    return view('login');
})->name("login");

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Admin Page
Route::get('/admin', function (){
    return view ('adminPage');
})->name('admin');

Route::view('/admin/{any}', 'adminPage')->where('any', '.*');


// User Data Page
// Return simple JSON about current session user for client-side auth checks
Route::get('/current-user', function () {
    return response()->json([
        'authenticated' => session()->has('user_id'),
        'user_id' => session('user_id') ?? null,
        'user_name' => session('user_name') ?? null,
    ]);
});

// Return user profile data as JSON
Route::get('/api/profile', function () {
    if (!session()->has('user_id')) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    $user = DB::table('users')
        ->select(
            'name',
            'email',
            'phone_number',
            'points',
            'profile_picture',
            'member_start'
        )
        ->where('user_id', session('user_id'))
        ->first();

    return response()->json($user);
});

// Handle profile picture upload
Route::post('/api/profile/avatar', function (Request $request) {

    if (!session()->has('user_id')) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    $request->validate([
        'avatar' => 'required|image|max:2048', // 2MB
    ]);

    $file = $request->file('avatar');

    // Generate unique filename
    $filename = uniqid('avatar_') . '.' . $file->getClientOriginalExtension();

    // Save to storage/app/public/avatars
    $path = $file->storeAs('avatars', $filename, 'public');

    // Save path to DB
    DB::table('users')
        ->where('user_id', session('user_id'))
        ->update([
            'profile_picture' => 'storage/' . $path
        ]);

    return response()->json([
        'status' => 'success',
        'path' => 'storage/' . $path
    ]);
});

// Update personal information
Route::post('/api/profile/personal', function (Request $request) {
    if (!session()->has('user_id')) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    $request->validate([
        'name'  => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'phone' => 'nullable|string|max:20',
    ]);

    // Prevent duplicate email (except self)
    $emailExists = DB::table('users')
        ->where('email', $request->email)
        ->where('user_id', '!=', session('user_id'))
        ->exists();

    if ($emailExists) {
        return response()->json(['error' => 'Email already in use'], 409);
    }

    DB::table('users')
        ->where('user_id', session('user_id'))
        ->update([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone,
        ]);

    // Update session name
    session(['user_name' => $request->name]);

    return response()->json(['status' => 'success']);
});

// Logout route
Route::post('/logout', function () {
    session()->flush();
    
    return response()->json([
        'status' => 'success',
        'message' => 'Logged out successfully'
    ]);
});

// Catch-all route for SPA client-side routes (prevents 404 on page refresh)
Route::get('/{any}', [Dummy::class, 'index'])->where('any', '.*');