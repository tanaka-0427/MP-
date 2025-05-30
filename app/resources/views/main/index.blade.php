@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">新着投稿</h1>

    @foreach ($posts as $post)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $post->title }}</h5>
                <p class="card-text">{{ Str::limit($post->description, 100) }}</p>
                <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">詳細を見る</a>
            </div>
        </div>
    @endforeach

    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>
@endsection
