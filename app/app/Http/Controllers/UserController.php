<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function mypage()
{
    $user = auth()->user();
    $myPosts = $user->posts()->latest()->get();

    return view('users.mypage', compact('myPosts'));
}
    public function profile()
{
    $user = auth()->user();
    return view('users.profile', compact('user'));
}
public function edit($id)
{
    $user = auth()->user();

    if ($user->id != $id) {
        abort(403); 
    }

    return view('users.edit', compact('user'));
}

public function update(Request $request, $id)
{
    $user = auth()->user();

    if ($user->id != $id) {
        abort(403);
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'profile' => 'nullable|string|max:1000',
        'icon' => 'nullable|image|max:2048',
    ]);

    $user->name = $request->name;
    $user->profile = $request->profile;

    if ($request->hasFile('icon')) {
        if ($user->icon) {
            Storage::delete('public/' . $user->icon);
        }
        $path = $request->file('icon')->store('icons', 'public');
        $user->icon = $path;
    }

    $user->save();

    return redirect()->route('profile')->with('success', 'プロフィールを更新しました');
}


}
