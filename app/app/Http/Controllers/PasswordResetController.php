<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    // GET /password-reset/create  パスワード再設定申請画面
    public function create()
    {
        return view('auth.password_reset_request');
    }

    // POST /password-reset  リセットメール送信処理
    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    // GET /password-reset/{token}/edit  パスワード変更フォーム
    public function edit($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    // PUT /password-reset/{token}  パスワード変更処理
    public function update(Request $request, $token)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect('/session/create')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
