@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">メインページ</h1>

    {{-- ナビゲーションボタン群 --}}
    <div class="mb-4 d-flex flex-wrap gap-2">
        <a href="{{ route('main') }}" class="btn btn-outline-primary">タイムライン</a>
        <a href="{{ route('posts.search') }}" class="btn btn-outline-secondary">投稿検索</a>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-success">カテゴリ一覧</a>
        <a href="{{ route('mypage') }}" class="btn btn-outline-dark">マイページ</a>
    </div>

    {{-- 投稿カード一覧 --}}
    @foreach ($posts as $post)
        <div class="card mb-3">
            <div class="row g-0">
                {{-- 投稿画像 --}}
                @if($post->image)
                    <div class="col-md-4">
                        <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid rounded-start" alt="投稿画像">
                    </div>
                @endif

                {{-- 投稿内容 --}}
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text">{{ Str::limit($post->content, 100) }}</p>
                        <p class="card-text">
                            <small class="text-muted">カテゴリ: {{ $post->category->name ?? '未分類' }}</small>
                        </p>
                        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">詳細を見る</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- ページネーション --}}
    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>
@endsection
