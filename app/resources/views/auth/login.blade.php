<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <title>ログイン - MP@</title>
</head>
<body>
    <h1>ログイン</h1>

    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('session.store') }}">
        @csrf
        <label for="email">メールアドレス：</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus><br>

        <label for="password">パスワード：</label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">ログイン</button>
    </form>

    <p>
    <a href="{{ route('register') }}">新規登録はこちら</a><br>
    <a href="{{ route('password.request') }}">パスワードを忘れた方はこちら</a>
    </p>
</body>
</html>
