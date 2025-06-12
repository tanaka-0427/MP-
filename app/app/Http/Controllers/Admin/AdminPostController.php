<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;

class AdminPostController extends Controller
{
   public function index()
{
    
    $posts = Post::with(['user', 'category'])->paginate(10);
    return view('admin.posts.index', compact('posts'));
}

    public function show($id)
    {
       
        $post = Post::with(['user', 'category'])->findOrFail($id);
        return view('admin.posts.show', compact('post'));
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $categories = Category::all();

        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price_original' => 'required|numeric',
            'price_purchased' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $validated['image'] = $path;
        }

        $post->update($validated);

        return redirect()->route('admin.posts.show', $post->id)->with('success', '投稿を更新しました');
    }
}
