<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;

class PostController extends Controller
{
    // 一覧表示
    public function index()
    {
        $posts = Post::with('user', 'category')->latest()->get();
        return view('posts.index', compact('posts'));
    }

    // 投稿作成フォーム表示
    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    // 投稿の保存
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price_original' => 'required|integer',
            'price_purchased' => 'required|integer',
            'image' => 'nullable|image|max:2048'
        ]);

        $post = new Post();
        $post->user_id = auth()->id();  // ログイン中のユーザーID
        $post->category_id = $request->category_id;
        $post->title = $request->title;
        $post->content = $request->content;
        $post->price_original = $request->price_original;
        $post->price_purchased = $request->price_purchased;

        if ($request->hasFile('image')) {
            $post->image = $request->file('image')->store('images', 'public');
        }

        $post->save();

        return redirect()->route('posts.index')->with('success', '投稿が作成されました');
    }

    // 投稿詳細表示
    public function show($id)
    {
        $post = Post::with('user', 'category')->findOrFail($id);
        return view('posts.show', compact('post'));
    }

    // 投稿編集フォーム
    public function edit($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $categories = Category::all();

        return view('posts.edit', compact('post', 'categories'));
    }

    // 投稿更新処理
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'title' => 'required|string|max:150',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price_original' => 'required|integer',
            'price_purchased' => 'required|integer',
            'image' => 'nullable|image|max:2048'
        ]);

        $post->category_id = $request->category_id;
        $post->title = $request->title;
        $post->content = $request->content;
        $post->price_original = $request->price_original;
        $post->price_purchased = $request->price_purchased;

        if ($request->hasFile('image')) {
            $post->image = $request->file('image')->store('images', 'public');
        }

        $post->save();

        return redirect()->route('posts.show', $post->id)->with('success', '投稿を更新しました');
    }

    // 投稿削除
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', '投稿を削除しました');
    }
}
