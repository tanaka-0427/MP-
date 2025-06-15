<!DOCTYPE html>
<html lang="ja">
<head>
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    <meta charset="UTF-8" />
    <title>新規登録 - MP@</title>
</head>
<body>
    <h1>新規登録</h1>

    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.store') }}">
        @csrf
        <label for="name">ユーザー名：</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus><br>

        <label for="email">メールアドレス：</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required><br>

        <label for="password">パスワード：</label>
        <input type="password" id="password" name="password" required><br>

        <label for="password_confirmation">パスワード（確認用）：</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required><br>

        <button type="submit">登録</button>
    </form>

    <p><a href="{{ route('session.create') }}">ログインはこちら</a></p>
</body>
</html>
