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
    
<div class="profile-edit-container">
    <h2 class="profile-edit-title">プロフィール編集</h2>

    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="profile-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">ニックネーム</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label for="profile">プロフィール</label>
            <textarea name="profile" id="profile" rows="4">{{ old('profile', $user->profile) }}</textarea>
        </div>

        <div class="form-group">
            <label for="icon">アイコン画像（任意）</label><br>
            @if($user->icon)
                <img src="{{ asset('storage/' . $user->icon) }}" alt="現在の画像" class="profile-icon-preview"><br>
            @endif
            <input type="file" name="icon" id="icon">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">更新する</button>
            <a href="{{ route('profile') }}" class="btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection
