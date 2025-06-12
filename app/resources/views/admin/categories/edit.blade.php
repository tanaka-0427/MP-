@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>カテゴリ編集・削除・追加</h2>

    {{-- カテゴリ選択プルダウン --}}
    <form method="GET" action="{{ route('admin.categories.editPage') }}" class="mb-3">
        <select name="category_id" onchange="this.form.submit()">
            <option value="">カテゴリを選択してください</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ optional($selectedCategory)->id == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </form>

    {{-- 選択中のカテゴリがあれば編集・削除フォーム --}}
    @if ($selectedCategory)
    <form method="POST" action="{{ route('admin.categories.update', $selectedCategory->id) }}">
        @csrf
        @method('PUT')
        <input type="text" name="name" value="{{ old('name', $selectedCategory->name) }}" required>
        <button type="submit">保存</button>
    </form>

    <form method="POST" action="{{ route('admin.categories.destroy', $selectedCategory->id) }}" onsubmit="return confirm('本当に削除しますか？')">
        @csrf
        @method('DELETE')
        <button type="submit">削除</button>
    </form>
    @endif

    {{-- 新規カテゴリ追加フォーム --}}
    <form method="POST" action="{{ route('admin.categories.store') }}" class="mt-4">
        @csrf
        <input type="text" name="name" placeholder="新規カテゴリ名" required>
        <button type="submit">追加</button>
    </form>

</div>
@endsection
