<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // カテゴリ一覧表示
    public function index()
    {
        $categories = Category::withCount('posts')->get();
        return view('categories.index', compact('categories'));
    }
    public function show($id)
    {
        $category = Category::findOrFail($id);
        $posts = $category->posts()->latest()->paginate(10);
        return view('categories.show', compact('category', 'posts'));
    }
    // カテゴリ作成フォーム
    public function create()
    {
        return view('categories.create');
    }

    // カテゴリ保存
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'カテゴリを作成しました');
    }

    // 編集フォーム
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    // 更新処理
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'カテゴリを更新しました');
    }

    // 削除
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'カテゴリを削除しました');
    }
}
