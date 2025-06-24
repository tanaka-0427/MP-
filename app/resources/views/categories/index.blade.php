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
    
<h2>カテゴリ一覧</h2>
<ul>
    @foreach ($categories as $category)
        <li>
            <a href="{{ route('categories.show', $category->id) }}">
                {{ $category->name }}（{{ $category->posts_count }}件）
            </a>
        </li>
    @endforeach
</ul>
@endsection
