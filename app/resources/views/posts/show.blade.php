@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid" alt="商品画像">
        </div>
        <div class="col-md-6">
            <h2>{{ $post->title }}</h2>
            <p><strong>定価:</strong> ¥{{ number_format($post->price_original) }}</p>
            <p><strong>購入価格:</strong> ¥{{ number_format($post->price_purchased) }}</p>
            <p><strong>カテゴリ:</strong> {{ $post->category->name }}</p>
            <div class="d-flex align-items-center mb-3">
                <img src="{{ asset('storage/' . $post->user->profile_image) }}" alt="投稿者" class="rounded-circle" width="50">
                <a href="{{ route('users.show', $post->user->id) }}" class="ms-2">{{ $post->user->name }}</a>
            </div>
            <p>{{ $post->content }}</p>
            
            <!-- いいね -->
            <button id="like-btn" class="btn btn-outline-danger" data-post-id="{{ $post->id }}">
                ❤️ いいね <span id="like-count">{{ $post->likes->count() }}</span>
            </button>

            <!-- お気に入り -->
            <form action="{{ route('favorites.store') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <button type="submit" class="btn btn-outline-warning">⭐ お気に入り</button>
            </form>
        </div>
    </div>

    <!-- コメント欄 -->
    <div class="mb-4">
        <h4>コメント</h4>
        <div id="comment-list">
            @foreach($post->comments as $comment)
                <div class="border p-2 mb-2">
                    <strong>{{ $comment->user->name }}:</strong> {{ $comment->comment }}
                </div>
            @endforeach
        </div>

        <form id="comment-form">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <textarea name="comment" class="form-control mb-2" rows="2" placeholder="コメントを入力"></textarea>
            <button type="submit" class="btn btn-primary">投稿</button>
        </form>
    </div>

    <!-- 相場情報 -->
    <div class="mb-4">
        <h4>相場情報</h4>
        <div class="row">
            <div class="col-md-6">
                <h6>メルカリ</h6>
                <iframe src="https://www.mercari.com/jp/search/?keyword={{ urlencode($post->title) }}" width="100%" height="300"></iframe>
            </div>
            <div class="col-md-6">
                <h6>ヤフオク</h6>
                <iframe src="https://auctions.yahoo.co.jp/search/search?p={{ urlencode($post->title) }}" width="100%" height="300"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('like-btn').addEventListener('click', function () {
    const postId = this.dataset.postId;
    fetch(`/likes/${postId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('like-count').textContent = data.likes_count;
    });
});

document.getElementById('comment-form').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('/comments', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        const commentList = document.getElementById('comment-list');
        commentList.innerHTML += `<div class="border p-2 mb-2"><strong>${data.user}:</strong> ${data.comment}</div>`;
        this.reset();
    });
});
</script>
@endsection
