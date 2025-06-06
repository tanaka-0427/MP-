@extends('layouts.app')

@section('content')
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
