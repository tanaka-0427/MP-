@extends('errors.layout')

@section('content')
<div class="container text-center mt-5">
    <h1 class="mb-4">システムエラーが発生しました</h1>
    <p class="mb-4">申し訳ありません。時間を置いて再度お試しください。</p>
    <a href="{{ route('main') }}" class="btn btn-primary">TOPページへ戻る</a>
</div>
@endsection
