@extends('layouts.admin') 

@section('content')
<div class="container mt-4">
    <h1>管理者トップページ</h1>

    {{-- 管理者メニュー --}}
    <div class="list-group mt-5" style="max-width: 400px;">
        <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action">
            ユーザー管理
        </a>
        <a href="{{ route('admin.posts.index') }}" class="list-group-item list-group-item-action">
            投稿管理
        </a>
        <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action">
            カテゴリ管理
        </a>
    </div>
</div>
@endsection
