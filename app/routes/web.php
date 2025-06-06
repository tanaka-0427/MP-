<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoritesController;

// --- 認証不要ページ（ログイン前） ---

// ログインページ（トップ）
Route::get('/', [SessionController::class, 'create'])->name('login');

// ログイン処理
Route::resource('session', SessionController::class)->only(['create', 'store']);

// ログアウト
Route::delete('/logout', [SessionController::class, 'destroy'])->name('logout');

// 新規登録
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// パスワード再設定
Route::get('password-reset/create', [PasswordResetController::class, 'create'])->name('password.request');   
Route::post('password-reset', [PasswordResetController::class, 'store'])->name('password.email');            
Route::get('password-reset/{token}/edit', [PasswordResetController::class, 'edit'])->name('password.reset'); 
Route::post('password-reset/{token}', [PasswordResetController::class, 'update'])->name('password.update');  


// --- 認証後ページ ---

Route::middleware(['auth'])->group(function () {

    // メインページ（タイムライン）
   Route::get('/main', [PostController::class, 'index'])->name('main');
    
    // 投稿検索
    Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');

    // カテゴリ一覧
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
    // マイページ
    Route::get('/mypage', [UserController::class, 'mypage'])->name('mypage');

    // ユーザー編集ページ
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    //ユーザー詳細
   Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    // お気に入り一覧
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoritesController::class, 'store'])->name('favorites.store');
    // 投稿CRUD
    Route::resource('posts', PostController::class);

    // いいね（Ajax）
    Route::post('/like/toggle', [LikeController::class, 'toggle'])->name('like.toggle');
    Route::post('/likes/{post}', [LikeController::class, 'toggle'])->name('likes.toggle');
    // コメント投稿（Ajax）
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
});
