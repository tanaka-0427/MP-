@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">ユーザー管理</h2>

    <!-- 検索フォーム -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-4">
        <div class="col-md-6">
            <input type="text" name="keyword" class="form-control" placeholder="名前またはメールアドレスで検索" value="{{ request('keyword') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">検索</button>
        </div>
    </form>

    <!--  ユーザー情報カード -->
    @if ($users->count())
        <p>{{ $users->total() }} 件のユーザーが見つかりました。</p>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($users as $user)
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $user->name }}</h5>
                            <p class="card-text">
                                メール: {{ $user->email }}<br>
                                登録日: {{ $user->created_at->format('Y-m-d') }}
                            </p>
    
    <!-- 詳細・編集・削除ボタン -->
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-outline-secondary btn-sm">詳細</a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-primary btn-sm">編集</a>
    
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('このユーザーを削除してもよろしいですか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">削除</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ページネーション -->
        <div class="mt-4">
            {{ $users->appends(request()->input())->links() }}
        </div>
    @else
        <p>ユーザーが見つかりませんでした。</p>
    @endif
</div>
@endsection
