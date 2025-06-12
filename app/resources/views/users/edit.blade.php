@extends('layouts.app')

@section('content')
<div class="container">
    <h2>プロフィール編集</h2>

    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">ニックネーム</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="profile" class="form-label">プロフィール</label>
            <textarea name="profile" id="profile" class="form-control" rows="4">{{ old('profile', $user->profile) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="icon" class="form-label">アイコン画像（任意）</label><br>
            @if($user->icon)
                <img src="{{ asset('storage/' . $user->icon) }}" alt="現在の画像" class="rounded" width="80" height="80"><br>
            @endif
            <input type="file" name="icon" id="icon" class="form-control mt-2">
        </div>

        <button type="submit" class="btn btn-primary">更新する</button>
        <a href="{{ route('profile') }}" class="btn btn-secondary">キャンセル</a>
    </form>
</div>
@endsection
