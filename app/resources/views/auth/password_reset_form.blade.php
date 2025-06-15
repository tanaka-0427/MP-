<!DOCTYPE html>
<html lang="ja">
<head>
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    <meta charset="UTF-8" />
    <title>パスワード再設定 - MP@</title>
</head>
<body>
    <h1>パスワード再設定</h1>

    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password_reset.update', ['token' => $token]) }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <label for="email">メールアドレス：</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus><br>

        <label for="password">新しいパスワード：</label>
        <input type="password" id="password" name="password" required><br>

        <label for="password_confirmation">新しいパスワード（確認用）：</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required><br>

        <button type="submit">パスワードを再設定する</button>
    </form>

    <p><a href="{{ route('session.create') }}">ログインはこちら</a></p>
</body>
</html>
