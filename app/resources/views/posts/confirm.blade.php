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
    
    <h2>投稿確認</h2>

    <table class="table">
        <tr>
            <th>商品名</th>
            <td>{{ $data['title'] }}</td>
        </tr>
        <tr>
            <th>定価</th>
            <td>￥{{ number_format($data['price_original']) }}</td>
        </tr>
        <tr>
            <th>購入価格</th>
            <td>￥{{ number_format($data['price_purchased']) }}</td>
        </tr>
        <tr>
            <th>カテゴリ</th>
            <td>{{ $categoryName }}</td>
        </tr>
        <tr>
            <th>レビュー</th>
            <td>{!! nl2br(e($data['content'])) !!}</td>
        </tr>
        <tr>
            <th>現在の相場</th>
            <td>@if($data['price_current']) ￥{{ number_format($data['price_current']) }} @else 未入力 @endif</td>
        </tr>
        @if(isset($imageUrl))
        <tr>
            <th>画像</th>
            <td><img src="{{ $imageUrl }}" alt="商品画像" style="max-height: 150px;"></td>
        </tr>
        @endif
    </table>

    <form action="{{ isset($post) ? route('posts.update', $post->id) : route('posts.store') }}" method="POST">
        @csrf
        @if(isset($post))
            @method('PUT')
        @endif

        {{-- hidden inputs to keep data --}}
        @foreach ($data as $key => $value)
            @if ($key !== 'image')
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach

        <button type="submit" name="action" value="back" class="btn btn-secondary">編集に戻る</button>
        <button type="submit" name="action" value="submit" class="btn btn-primary">投稿する</button>
    </form>
</div>
@endsection
