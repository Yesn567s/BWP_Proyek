<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\TicketProductController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ScheduleController;

Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/now-playing', [MovieController::class, 'nowPlaying']);
Route::get('/movies/upcoming', [MovieController::class, 'coomingSoon']);
Route::get('/movies/trailers', [MovieController::class, 'trailers']);

Route::get('/stats', function () {
    return response()->json([
        'totalProducts' => DB::table('ticket_products')->count(),
        'totalVenues'   => DB::table('venues')->count(),
        'totalOrders'   => DB::table('orders')->count(),
        'totalUsers'    => DB::table('users')->count(),
    ]);
});
Route::get('/categories', [TicketCategoryController::class, 'index']);
Route::get('/vouchers', [VoucherController::class, 'index']);
Route::get('/tickets', [TicketProductController::class, 'index']);
Route::get('/movies/{id}/schedule', [ScheduleController::class, 'show']);

// routes/api.php
// Route::post('/admin/login', [AuthController::class, 'login']);

    