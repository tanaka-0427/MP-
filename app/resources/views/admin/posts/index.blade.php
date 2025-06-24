@extends('layouts.admin')

@section('title', '投稿一覧')

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
    
    <h2>投稿一覧</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>タイトル</th>
                <th>カテゴリ</th>
                <th>投稿者</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->category->name ?? '未分類' }}</td>
                    <td>{{ $post->user->name ?? '不明' }}</td>
                    <td>
                        <a href="{{ route('admin.posts.show', $post->id) }}" class="btn btn-sm btn-info">詳細</a>
                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-sm btn-warning">編集</a>
                        <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('本当に削除しますか？');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $posts->links() }}
</div>
@endsection
