<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TicketCategoryController;

Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/now-playing', [MovieController::class, 'nowPlaying']);
Route::get('/movies/upcoming', [MovieController::class, 'coomingSoon']);
Route::get('/movies/trailers', [MovieController::class, 'trailers']);
Route::get('/categories', [TicketCategoryController::class, 'index']);
