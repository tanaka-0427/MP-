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
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminAuthController;
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
    //相場
    Route::get('/scraping/{keyword}', [App\Http\Controllers\ScrapingController::class, 'show']);
    // カテゴリ一覧
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
    // マイページ
    Route::get('/mypage', [UserController::class, 'mypage'])->name('mypage');
    // プロフィールページ（ログインユーザー自身）
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    // ユーザー編集ページ
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    //プロフィール編集
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    //ユーザー詳細
   Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
   
    // お気に入り一覧
    Route::get('/favorites', [FavoritesController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{post}', [FavoritesController::class, 'store'])->name('favorites.store');
    // 投稿CRUD
    Route::resource('posts', PostController::class);

    // いいね（Ajax）
    Route::post('/likes/{post}', [LikeController::class, 'toggle'])->name('likes.toggle');   
    // コメント投稿（Ajax）
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::put('/comments/{id}', [CommentController::class, 'update'])->name('comments.update');

});
    Route::prefix('admin')
            ->name('admin.')
            ->middleware(['auth', 'admin']) 
            ->group(function () {

    // 管理者用ダッシュボード
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // 管理者 ユーザー一覧・詳細
    Route::resource('users', AdminUserController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);

    // 管理者 投稿一覧・詳細
    Route::resource('posts', AdminPostController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);

    // 管理者 カテゴリ一覧
     Route::resource('categories', AdminCategoryController::class)->only(['index', 'edit', 'update', 'destroy', 'create', 'store']);
      Route::get('categories', [AdminCategoryController::class, 'index'])->name('categories.index');

    // カテゴリ編集ページ
    Route::get('categories/edit', [AdminCategoryController::class, 'editPage'])->name('categories.editPage');

    // カテゴリ編集処理（パラメータ付き）
    Route::put('categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');

    // カテゴリ削除
    Route::delete('categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // 新規カテゴリ追加
    Route::post('categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    
});

  Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
