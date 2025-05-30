<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PasswordResetController;

// トップページ
Route::get('/', [SessionController::class, 'create'])->name('login');

// ログアウトルート
Route::delete('/logout', [SessionController::class, 'destroy'])->name('logout');

// ログイン
Route::resource('session', SessionController::class)->only(['create', 'store']);

// 新規登録フォーム
Route::get('/register', [RegisterController::class, 'create'])->name('register');

// 新規登録処理
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// パスワード再設定
Route::resource('password-reset', PasswordResetController::class)->only(['create', 'store', 'edit', 'update']);

// 認証済ユーザーのみアクセス可能
Route::middleware(['auth'])->group(function () {
    Route::get('/main', [MainPageController::class, 'index'])->name('main.index');
});
