@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>カテゴリ管理</h2>
    <table class="table">
        <thead>
            <tr>
                <th>カテゴリ名</th>
                <th>編集</th>
                <th>削除</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>
                    <a href="{{ route('admin.categories.editPage', ['category_id' => $category->id]) }}" class="btn btn-primary">編集</a>
                </td>
                <td>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" onsubmit="return confirm('本当に削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.categories.editPage') }}" class="btn btn-success">新規カテゴリ追加</a>
</div>
@endsection
