@extends('errors.layout')

@section('content')
<div class="container text-center mt-5">
    <h1 class="mb-4">エラー</h1>
    <p class="mb-4">不正なアクセスです。</p>
    <a href="{{ route('main') }}" class="btn btn-primary">TOPページへ戻る</a>
</div>
@endsection