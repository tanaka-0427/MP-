@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>ユーザー編集</h2>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">名前</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">メールアドレス</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">更新</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">戻る</a>
    </form>
</div>
@endsection
