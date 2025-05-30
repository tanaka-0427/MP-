<!-- resources/views/auth/password_reset_request.blade.php -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <title>パスワード再設定メール送信 - MP@</title>
</head>
<body>
    <h1>パスワード再設定メール送信</h1>

    @if (session('status'))
        <div style="color:green;">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password-reset.store') }}">
        @csrf
        <label for="email">登録済みのメールアドレス：</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus><br>

        <button type="submit">パスワード再設定メールを送信</button>
    </form>

    <p><a href="{{ route('session.create') }}">ログインはこちら</a></p>
</body>
</html>
