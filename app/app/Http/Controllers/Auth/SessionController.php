<?php
namespace App\Http\Controllers\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    //  ログイン画面
    public function create()
    {
        return view('auth.login');
    }

    //  ログイン処理
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('main');
        }

        return back()->withErrors(['email' => '認証に失敗しました']);
    }

    //  ログアウト処理
    public function destroy(Request $request, $id = null)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/session/create');
    }
}
