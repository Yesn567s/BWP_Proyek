<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    public function index()
    {
        $data = Post::all();
        return response()->json($data);
    }

    public function show($id)
    {
        $data = Post::find($id);
        if ($data) {
            return response()->json($data);
        } else {
            return response()->json(['message' => 'Post not found'], 404);
        }
    }
}
