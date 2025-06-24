@extends('layouts.admin')

@section('title', '投稿編集')

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
    
    <h2>投稿編集</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- 商品名 -->
        <div class="mb-3">
            <label for="title" class="form-label">商品名</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $post->title) }}" required>
        </div>

        <!-- 定価 -->
        <div class="mb-3">
            <label for="price_original" class="form-label">定価</label>
            <input type="number" name="price_original" id="price_original" class="form-control" value="{{ old('price_original', $post->price_original) }}" required>
        </div>

        <!-- 購入価格 -->
        <div class="mb-3">
            <label for="price_purchased" class="form-label">購入価格</label>
            <input type="number" name="price_purchased" id="price_purchased" class="form-control" value="{{ old('price_purchased', $post->price_purchased) }}" required>
        </div>

        <!-- カテゴリ -->
        <div class="mb-3">
            <label for="category_id" class="form-label">カテゴリ</label>
            <select name="category_id" id="category_id" class="form-select" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- レビュー内容 -->
        <div class="mb-3">
            <label for="content" class="form-label">レビュー</label>
            <textarea name="content" id="content" class="form-control" rows="4" required>{{ old('content', $post->content) }}</textarea>
        </div>

        <!-- 画像表示＆更新 -->
        <div class="mb-3">
            <label class="form-label">現在の画像</label><br>
            @if ($post->image)
                <img src="{{ asset('storage/' . $post->image) }}" alt="現在の画像" class="img-thumbnail mb-2" width="200">
            @else
                <p>画像は登録されていません。</p>
            @endif

            <label for="image" class="form-label">画像を変更</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>

        <!-- 更新ボタン -->
        <button type="submit" class="btn btn-primary">更新する</button>
        <a href="{{ route('admin.posts.show', $post->id) }}" class="btn btn-secondary">戻る</a>
    </form>
</div>
@endsection
