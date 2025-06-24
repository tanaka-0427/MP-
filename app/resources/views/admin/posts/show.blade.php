@extends('layouts.admin')

@section('title', '投稿詳細')

@section('content')
<div class="container mt-4">
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
    
    <h2>投稿詳細</h2>

    <!-- 投稿情報 -->
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title">{{ $post->title }}</h4>
            <p><strong>定価:</strong> ¥{{ number_format($post->price_original) }}</p>
            <p><strong>購入価格:</strong> ¥{{ number_format($post->price_purchased) }}</p>
            <p><strong>カテゴリ:</strong> #{{ $post->category->name }}</p>
            <p><strong>レビュー:</strong><br>{{ $post->content }}</p>

            @if ($post->image)
                <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid mt-3" alt="投稿画像">
            @endif

            <hr>

            <p>
                <strong>投稿者:</strong>
                <a href="route('users.show', $post->user)" class="btn btn-sm btn-outline-secondary">
                    {{ $post->user->name }}
                </a>
            </p>

            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-warning">編集</a>

                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">削除</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
