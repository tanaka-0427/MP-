<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class AdminCategoryController extends Controller
{
    // 一覧ページ
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    // 編集ページ
    public function editPage(Request $request)
    {
        $categories = Category::all();
        $selectedCategory = null;

        if ($request->has('category_id')) {
            $selectedCategory = Category::find($request->input('category_id'));
        }

        return view('admin.categories.edit', compact('categories', 'selectedCategory'));
    }

    // 更新処理
    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category->name = $request->input('name');
        $category->save();

        return redirect()->route('admin.categories.editPage', ['category_id' => $category->id])->with('success', '更新しました');
    }

    // 削除処理
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.editPage')->with('success', '削除しました');
    }

    // 新規追加処理
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Category::create(['name' => $request->input('name')]);
        return redirect()->route('admin.categories.editPage')->with('success', '新規追加しました');
    }
}
