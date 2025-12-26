<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dummy;

// Route::get('/', function () {
//     return view('dummy');
// });

Route::get('/', [Dummy::class, "index"])->name("index");

Route::get('/login', function () {
    return view('login');
})->name("login");

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/admin', function (){
    return view ('adminPage');
})->name('admin');

Route::view('/admin/{any}', 'adminPage')->where('any', '.*');

// Catch-all route for SPA client-side routes (prevents 404 on page refresh)
Route::get('/{any}', [Dummy::class, 'index'])->where('any', '.*');