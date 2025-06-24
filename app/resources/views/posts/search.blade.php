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
    
    <h2>投稿検索</h2>

    <!-- 検索フォーム -->
    <form method="GET" action="{{ route('posts.search') }}">
        <div class="mb-3">
            <input type="text" name="q" class="form-control" value="{{ old('q', $query) }}" placeholder="キーワードを入力">
        </div>
        <button type="submit" class="btn btn-primary">検索</button>
    </form>

    <hr>

    <!-- 投稿カード表示 -->
    @if($posts->count())
        <div class="row">
            @foreach($posts as $post)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="投稿画像">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{{ Str::limit($post->content, 100) }}</p>
                            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-outline-primary">詳細を見る</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

      
        {{ $posts->links() }}

    @else
        <p>該当する投稿はありません。</p>
    @endif
</div>
@endsection
