<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $users = User::when($keyword, function ($query, $keyword) {
            return $query->where('name', 'like', "%{$keyword}%")
                         ->orWhere('email', 'like', "%{$keyword}%");
        })->paginate(10);

        return view('admin.users.index', compact('users', 'keyword'));
    }
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }
    public function edit($id)
{
    $user = User::findOrFail($id);
    return view('admin.users.edit', compact('user'));
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
    ]);

    $user->update($validated);

    return redirect()->route('admin.users.index')->with('success', 'ユーザー情報を更新しました');
}

public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return redirect()->route('admin.users.index')->with('success', 'ユーザーを削除しました');
}
}
