<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Dummy extends Controller
{
    function index(){
        $users = DB::table('users')->get();
        return view('dummy', ['users' => $users]);
    }
}
