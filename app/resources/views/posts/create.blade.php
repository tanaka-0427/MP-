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
    
    <h2>{{ isset($post->id) ? '投稿編集' : '新規投稿' }}</h2>

<form action="{{ isset($post->id) ? route('posts.update', $post->id) : route('posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($post->id))
        @method('PUT')
    @endif

        <div class="mb-3">
            <label for="title" class="form-label">商品名</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $post->title ?? '') }}" required maxlength="150">
        </div>

        <div class="row mb-3">
            <div class="col">
                <label for="price_original" class="form-label">定価（￥）</label>
                <input type="number" class="form-control" id="price_original" name="price_original" value="{{ old('price_original', $post->price_original ?? '') }}" required min="0">
            </div>
            <div class="col">
                <label for="price_purchased" class="form-label">購入価格（￥）</label>
                <input type="number" class="form-control" id="price_purchased" name="price_purchased" value="{{ old('price_purchased', $post->price_purchased ?? '') }}" required min="0">
            </div>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">カテゴリ</label>
            <select class="form-select" id="category_id" name="category_id" required>
                <option value="">選択してください</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ (old('category_id', $post->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">画像アップロード</label>
            <input class="form-control" type="file" id="image" name="image" accept="image/*">
            @if(isset($post) && $post->image)
                <small>現在の画像: <img src="{{ asset('storage/' . $post->image) }}" alt="商品画像" style="max-height: 80px;"></small>
            @endif
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">レビュー</label>
            <textarea class="form-control" id="content" name="content" rows="5" required>{{ old('content', $post->content ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="price_current" class="form-label">現在の相場（￥）</label>
            <input type="number" class="form-control" id="price_current" name="price_current" value="{{ old('price_current', $post->price_current ?? '') }}" min="0">
        </div>

        <button type="submit" name="action" value="confirm" class="btn btn-primary">投稿確認</button>
    </form>
</div>
@endsection
