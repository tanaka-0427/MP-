@extends('layouts.admin')

@section('title', 'ユーザー詳細')

@section('content')
<div class="container mt-4">
    <h2>ユーザー詳細</h2>

    <!-- ユーザー基本情報 -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ $user->name }}</h5>
            <p class="card-text"><strong>メールアドレス:</strong> {{ $user->email }}</p>
            <p class="card-text"><strong>登録日:</strong> {{ $user->created_at->format('Y年m月d日 H:i') }}</p>
        </div>
    </div>

    <!-- 投稿履歴一覧 -->
    <h4>投稿履歴</h4>
    @if ($user->posts->count() > 0)
        <div class="row">
            @foreach ($user->posts as $post)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        @if ($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="投稿画像">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{{ Str::limit($post->content, 50) }}</p>
                            <a href="{{ route('admin.posts.show', $post->id) }}" class="btn btn-sm btn-outline-primary">詳細を見る</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>投稿履歴はありません。</p>
    @endif
</div>
@endsection
