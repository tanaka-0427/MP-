@extends('layouts.app')

@section('content')
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
    
<h2>{{ $category->name }} の投稿一覧</h2>

@foreach ($posts as $post)
    <div class="card mb-3">
        <h3>{{ $post->title }}</h3>
         <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid" alt="商品画像">
        <p>{{ Str::limit($post->content, 100) }}</p>
        <a href="{{ route('posts.show', $post->id) }}">詳細を見る</a>
    </div>
@endforeach

{{ $posts->links() }}
@endsection
