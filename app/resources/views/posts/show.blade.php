@extends('layouts.app')

@section('content')

<div class="container">
     {{-- 成功メッセージの表示 --}}
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    {{-- エラーメッセージの表示 --}}
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    
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
                <img src="{{ asset('storage/' . $post->user->icon) }}" alt="投稿者" class="rounded-circle" width="50">
                <a href="{{ route('users.show', $post->user->id) }}" class="ms-2">{{ $post->user->name }}</a>
            </div>
            <p>{{ $post->content }}</p>

            @auth
                <!-- いいね -->
                <button id="like-btn" class="btn btn-outline-danger" data-post-id="{{ $post->id }}">
                    ❤️ いいね <span id="like-count">{{ $post->likes->count() }}</span>
                </button>

                <!-- お気に入り -->
                <form action="{{ route('favorites.store', $post->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <button type="submit" class="btn btn-outline-warning">⭐ お気に入り</button>
                </form>
            @endauth
        </div>
    </div>

    <!-- コメント欄 -->
    <div class="mb-4">
        <h4>コメント</h4>
        <div id="comment-list">
            @foreach($post->comments as $comment)
                <div class="border p-2 mb-2" data-comment-id="{{ $comment->id }}">
                    <strong>{{ $comment->user->name }}:</strong>
                    <span class="comment-text">{{ $comment->comment }}</span>

                    @if ($comment->user_id === auth()->id())
                        <div class="mt-1">
                            <button class="btn btn-sm btn-secondary edit-comment-btn">編集</button>
                            <button class="btn btn-sm btn-danger delete-comment-btn">削除</button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @auth
            <form id="comment-form">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <textarea name="comment" class="form-control mb-2" rows="2" placeholder="コメントを入力" required></textarea>
                <button type="submit" class="btn btn-primary">投稿</button>
            </form>
        @endauth

        @guest
            <p>ログインしてコメントやいいねをしましょう。</p>
        @endguest
    </div>

    <!-- 相場情報 -->
    <div class="mb-4">
    <h4>相場情報</h4>
    <div class="mb-4">
    <h4>ヤフオク価格推移</h4>
    <canvas id="priceChart" width="400" height="200"></canvas>
    </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.getElementById('like-btn')?.addEventListener('click', function () {
    const btn = this;
    const postId = btn.dataset.postId;

    const formData = new FormData();
    formData.append('post_id', postId);

    fetch('/likes/toggle', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('like-count').textContent = data.count;

        if(data.liked) {
            btn.classList.remove('btn-outline-danger');
            btn.classList.add('btn-danger');
        } else {
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-outline-danger');
        }
    });
});

// コメント投稿
document.getElementById('comment-form')?.addEventListener('submit', function (e) {
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
        commentList.innerHTML += `
            <div class="border p-2 mb-2" data-comment-id="${data.id}">
                <strong>${data.user}:</strong>
                <span class="comment-text">${data.comment}</span>
                <div class="mt-1">
                    <button class="btn btn-sm btn-secondary edit-comment-btn">編集</button>
                    <button class="btn btn-sm btn-danger delete-comment-btn">削除</button>
                </div>
            </div>
        `;
        this.reset();
    });
});

// 編集・削除処理
document.addEventListener('click', function(e) {
    // 削除
    if (e.target.classList.contains('delete-comment-btn')) {
        const commentDiv = e.target.closest('[data-comment-id]');
        const commentId = commentDiv?.dataset?.commentId;

        if (confirm('本当に削除しますか？')) {
            fetch(`/comments/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    commentDiv.remove();
                } else {
                    alert('削除に失敗しました。');
                }
            })
            .catch(err => {
                console.error('エラー:', err);
                alert('通信エラーが発生しました');
            });
        }
    }

    // 編集モード
    if (e.target.classList.contains('edit-comment-btn')) {
        const commentDiv = e.target.closest('[data-comment-id]');
        const commentText = commentDiv.querySelector('.comment-text');
        const oldComment = commentText.textContent;

        commentText.innerHTML = `
            <textarea class="form-control mb-2 edit-area">${oldComment}</textarea>
            <button class="btn btn-sm btn-primary save-comment-btn">保存</button>
        `;
        e.target.style.display = 'none';
    }

    // 編集保存
    if (e.target.classList.contains('save-comment-btn')) {
        const commentDiv = e.target.closest('[data-comment-id]');
        const commentId = commentDiv.dataset.commentId;
        const newComment = commentDiv.querySelector('.edit-area').value;

        fetch(`/comments/${commentId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ comment: newComment })
        })
        .then(res => {
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        })
        .then(data => {
            if (data.success) {
                const commentText = commentDiv.querySelector('.comment-text');
                commentText.textContent = data.comment;
                commentDiv.querySelector('.edit-comment-btn').style.display = 'inline-block';
            } else {
                alert('保存に失敗しました。');
            }
        })
        .catch(err => {
            console.error('エラー:', err);
            alert('通信エラーが発生しました');
        });
    }
});

    const yahooData = @json($yahooPricesByDate);
    const mercariData = @json($mercariPricesByDate);

    // 日付
    const allDates = [...new Set([...yahooData.map(item => item.date), ...mercariData.map(item => item.date)])].sort();

    const yahooMap = new Map(yahooData.map(item => [item.date, item.avg_price]));
    const mercariMap = new Map(mercariData.map(item => [item.date, item.avg_price]));

    const yahooPrices = allDates.map(date => yahooMap.get(date) || null);
    const mercariPrices = allDates.map(date => mercariMap.get(date) || null);

    const ctx = document.getElementById('priceChart').getContext('2d');
    const priceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: allDates,
            datasets: [
                {
                    label: 'ヤフオク平均価格',
                    data: yahooPrices,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: false,
                    tension: 0.1
                },
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'ヤフオク 平均価格推移'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: '日付'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: '価格 (円)'
                    },
                    beginAtZero: true
                }
            }
        }
    });
    // 平均価格を計算
    function calcAverage(dataArray) {
    const valid = dataArray.filter(price => typeof price === 'number');
    const total = valid.reduce((sum, price) => sum + price, 0);
    return valid.length > 0 ? Math.round(total / valid.length) : 0;
    }

    const yahooAvg = calcAverage(yahooPrices);

    document.getElementById('yahooAverage').textContent = `ヤフオク全体平均価格: ${yahooAvg.toLocaleString()}円`;

</script>

    
@endsection
