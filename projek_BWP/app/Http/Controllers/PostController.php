<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index()
    {
        return response()->json(
            Post::orderBy('created_at', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Post::create($validated);

        return response()->json($post, 201);
    }

    // DELETE post
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }

    public function show($id)
{
    $post = DB::table('post')
        ->where('post_id', $id)
        ->first();

    if (!$post) {
        return response()->json([
            'message' => 'Post not found'
        ], 404);
    }

    return response()->json($post);
}


    public function update(Request $request, $id)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $updated = DB::table('post')
            ->where('post_id', $id)
            ->update([
                'title'      => $request->title,
                'content'    => $request->content,
                'created_at' => now(),
            ]);

        if (!$updated) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Post updated successfully'
        ]);
    }

}
