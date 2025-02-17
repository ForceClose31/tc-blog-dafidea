<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        return response()->json(Post::with('user')->latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_rilis' => 'required|date',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('gambar') ? $request->file('gambar')->store('posts', 'public') : null;

        $post = Post::create([
            'judul' => $request->judul,
            'tanggal_rilis' => $request->tanggal_rilis,
            'deskripsi' => $request->deskripsi,
            'gambar' => $path,
            'user_id' => Auth::id(),
        ]);

        return response()->json($post);
    }

    public function show($id)
    {
        $post = Post::with('user', 'comments.user')->findOrFail($id);
        return response()->json($post);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_rilis' => 'required|date',
            'deskripsi' => 'required|string',
            'pict' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('pict')) {
            $path = $request->file('pict')->store('posts', 'public');
            $post->pict = $path;
        }

        $post->update($request->only(['judul', 'tanggal_rilis', 'deskripsi']));
        return response()->json($post);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }
}
