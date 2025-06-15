<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'エラーが発生しました')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="text-center">
        <h1 class="mb-3">@yield('title')</h1>
        <p class="mb-4">@yield('message')</p>
        <a href="{{ route('main') }}" class="btn btn-primary">TOPページへ戻る</a>
    </div>
</body>
</html>
