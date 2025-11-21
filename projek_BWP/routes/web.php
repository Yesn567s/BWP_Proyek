<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dummy;

// Route::get('/', function () {
//     return view('dummy');
// });

Route::get('/', [Dummy::class, "index"])->name("index");