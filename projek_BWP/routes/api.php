<?php

use App\Http\Controllers\AdminMovieController;
use App\Http\Controllers\AdminScheduleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoodController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\TicketProductController;
use App\Http\Controllers\TicketInstanceController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\StudioController;

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
Route::get('/yourTickets', [TicketInstanceController::class, 'index']);
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/{id}', [BlogController::class, 'show']);
Route::get('/movies/{id}/schedule', [ScheduleController::class, 'show']);
// Route::get('/movies/{id}/dates', [ScheduleController::class, 'datesByMovie']);
// Route::get('/movies/{id}/dates', [MovieController::class, 'dates']);
Route::get('/movies/{id}/dates', [ScheduleController::class, 'availableDates']);
Route::get('/schedules/{schedule}/seats', [ScheduleController::class, 'seats']);
Route::post('/admin/schedules', [AdminScheduleController::class, 'store']);

Route::get('/checkout', [CheckoutController::class, 'index']);
Route::get('/orders', [CheckoutController::class, 'orders']);

Route::get('/food/venues', [VenueController::class, 'foodVenues']);
Route::get('/food', [FoodController::class, 'allFood']);
Route::get('/cinema-partners', [VenueController::class, 'partners']);



Route::post('/admin/movies', [AdminMovieController::class, 'store']);
Route::get('/cinema-partners', [VenueController::class, 'partners']);
Route::get('/venues/{id}', [VenueController::class, 'show']);
Route::post('venues/{venue}/studios',[StudioController::class, 'store']);

