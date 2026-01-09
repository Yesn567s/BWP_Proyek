<?php

use App\Http\Controllers\AdminFoodController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminTransactionController;
use App\Http\Controllers\AdminMovieController;
use App\Http\Controllers\AdminScheduleController;
use App\Http\Controllers\AdminTixFunController;
use App\Http\Controllers\AdminVenueController;
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
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PostController;
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
Route::get('/vouchers/{code}', [VoucherController::class, 'showByCode']);
Route::get('/tickets', [TicketProductController::class, 'index']);
Route::get('/yourTickets', [TicketInstanceController::class, 'index'])->middleware('web');
Route::post('/tickets/redeem', [TicketInstanceController::class, 'redeem'])->middleware('web');
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/{id}', [BlogController::class, 'show']);
Route::get('/movies/{id}/schedule', [ScheduleController::class, 'show']);
// Route::get('/movies/{id}/dates', [ScheduleController::class, 'datesByMovie']);
// Route::get('/movies/{id}/dates', [MovieController::class, 'dates']);
Route::get('/movies/{id}/dates', [ScheduleController::class, 'availableDates']);
Route::get('/schedules/{schedule}/seats', [ScheduleController::class, 'seats']);
Route::post('/admin/schedules', [AdminScheduleController::class, 'store']);

Route::get('/checkout', [CheckoutController::class, 'index']);
Route::post('/orders', [CheckoutController::class, 'orders']);
Route::post('/orders', [CheckoutController::class, 'store'])->middleware('web');
Route::get('/vouchers/{id}', [CheckoutController::class, 'voucher']);

Route::get('/food/venues', [VenueController::class, 'foodVenues']);
Route::get('/food', [FoodController::class, 'allFood']);
Route::get('/food/{id}', [FoodController::class, 'show']);
Route::get('/cinema-partners', [VenueController::class, 'partners']);



Route::post('/admin/movies', [AdminMovieController::class, 'store']);
Route::put('/admin/movies/{product}', [AdminMovieController::class, 'update']);
Route::delete('/admin/movies/{product}', [AdminMovieController::class, 'destroy']);
Route::post('/admin/food', [AdminFoodController::class, 'store']);
Route::get('/admin/food/{id}', [AdminFoodController::class, 'show']);
Route::put('/admin/food/{id}', [AdminFoodController::class, 'update']);
Route::get('/cinema-partners', [VenueController::class, 'partners']);
Route::get('/venues/{id}', [VenueController::class, 'show']);
Route::post('venues/{venue}/studios',[StudioController::class, 'store']);
Route::get('/admin/venues-with-studios', [AdminVenueController::class, 'venuesWithStudios']);
Route::post('/admin/venues', [AdminVenueController::class, 'store']);
Route::post('/admin/tixfun', [AdminTixFunController::class, 'store']);
Route::get('/admin/tixfun/{id}', [AdminTixFunController::class, 'show']);
Route::put('/admin/tixfun/{id}', [AdminTixFunController::class, 'update']);
Route::delete('/admin/tixfun/{id}', [AdminTixFunController::class, 'destroy']);
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);
Route::get('/admin/transactions', [AdminTransactionController::class, 'index']);
Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::put('/posts/{id}', [PostController::class, 'update']);
