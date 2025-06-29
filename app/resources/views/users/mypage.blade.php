@extends('layouts.app')

@section('content')
<div class="container">
    {{-- 成功メッセージの表示 --}}
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    {{-- エラーメッセージの表示 --}}
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <h1 class="mb-4">マイページ</h1>

    {{-- ユーザー情報表示 --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ Auth::user()->name }} さん</h5>
            <p class="card-text">{{ Auth::user()->profile ?? 'プロフィール未設定' }}</p>
            <a href="{{ route('users.edit', auth()->user()->id) }}" class="btn btn-primary">編集する</a>
        </div>
    </div>

    {{-- 投稿一覧 --}}
    <h2 class="mb-3">投稿一覧</h2>
    @if($myPosts->isEmpty())
        <p>投稿がまだありません。</p>
    @else
        @foreach ($myPosts as $post)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }}</h5>
                     <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid" alt="商品画像">
                    <p class="card-text">{{ Str::limit($post->content, 100) }}</p>
                    <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">詳細</a>
                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-secondary">投稿編集</a>
                 {{-- 削除ボタン --}}
            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('本当に削除しますか？');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">削除</button>
            </form>
                </div>
            </div>
        @endforeach
    @endif
    <h2>おすすめの商品</h2>
    <div class="recommend-list">
    @foreach ($recommendedPosts as $post)
        <div class="post-card">
            <a href="{{ route('posts.show', $post) }}">
                 <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid" alt="商品画像">
                <p>{{ $post->title }}</p>
            </a>
        </div>
    @endforeach
    </div>
    {{-- お気に入り一覧リンク --}}
    <div class="mt-4">
        <a href="{{ route('favorites.index') }}" class="btn btn-outline-success">お気に入り一覧を見る</a>
    </div>
</div>
@endsection
