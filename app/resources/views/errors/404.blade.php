@extends('errors.layout')

@section('content')
<div class="container text-center mt-5">
    <h1 class="mb-4">ページが見つかりません</h1>
    <p class="mb-4">指定されたページは存在しません。</p>
    <a href="{{ route('main') }}" class="btn btn-primary">TOPページへ戻る</a>
</div>
@endsection