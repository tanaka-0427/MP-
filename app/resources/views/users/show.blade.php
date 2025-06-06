@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $user->name }} さんのプロフィール</h2>

    <div class="mb-4">
        <h5>プロフィール</h5>
        <p>{{ $user->profile ?? 'プロフィールは未設定です。' }}</p>
    </div>

    <div class="mb-4">
        <h5>投稿一覧</h5>
        @if($posts->isEmpty())
            <p>投稿がありません。</p>
        @else
            <div class="row row-cols-1 row-cols-md-2 g-4">
                @foreach($posts as $post)
                    <div class="col">
                        <div class="card h-100">
                            @if($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="{{ $post->title }}">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $post->title }}</h5>
                                <p class="card-text">{{ Str::limit($post->content, 60) }}</p>
                                <a href="{{ route('posts.detail', $post->id) }}" class="btn btn-sm btn-outline-primary">詳細を見る</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
