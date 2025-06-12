@extends('layouts.app')

@section('content')
<div class="container">
    <h2>プロフィール</h2>
    <div class="card mt-4">
        <div class="card-body">
            <div class="text-center mb-4">
                @if($user->icon)
                    <img src="{{ asset('storage/' . $user->icon) }}" alt="プロフィール画像" class="rounded-circle" width="120" height="120">
                @else
                    <img src="{{ asset('images/default-icon.png') }}" alt="デフォルト画像" class="rounded-circle" width="120" height="120">
                @endif
            </div>

            <p><strong>ニックネーム：</strong> {{ $user->name }}</p>
            <p><strong>メールアドレス：</strong> {{ $user->email }}</p>
            <p><strong>プロフィール：</strong><br> {{ $user->profile }}</p>

            <div class="mt-4">
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">編集する</a>
                <a href="{{ route('password.request') }}" class="btn btn-secondary">パスワード変更</a>
            </div>
        </div>
    </div>
</div>
@endsection
