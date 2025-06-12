@extends('layouts.app')

@section('content')
<div class="container">
    <h2>お気に入り一覧</h2>

    @if($favorites->isEmpty())
        <p>お気に入りに登録された商品はありません。</p>
    @else
        <div class="row">
            @foreach($favorites as $favorite)
                @php $post = $favorite->post; @endphp
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="{{ $post->title }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{{ Str::limit($post->content, 100) }}</p>
                            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">詳細を見る</a>
                        </div>

                        {{-- 価格推移チャート --}}
                        <div>
                            <canvas id="chart-{{ $post->id }}" width="400" height="200"></canvas>
                        </div>

                        @push('scripts')
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const ctx = document.getElementById('chart-{{ $post->id }}');
                                if (ctx) {
                                    new Chart(ctx, {
                                        type: 'line',
                                        data: {
                                            labels: {!! json_encode($post->priceHistories->pluck('recorded_at')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y/m/d'))) !!},
                                            datasets: [{
                                                label: '価格推移',
                                                data: {!! json_encode($post->priceHistories->pluck('price')) !!},
                                                borderColor: 'rgba(75, 192, 192, 1)',
                                                fill: false,
                                                tension: 0.1
                                            }]
                                        },
                                        options: {
                                            scales: {
                                                y: {
                                                    beginAtZero: true,
                                                    title: { display: true, text: '価格（円）' }
                                                },
                                                x: {
                                                    title: { display: true, text: '日付' }
                                                }
                                            }
                                        }
                                    });
                                }
                            });
                        </script>
                        @endpush

                    </div>
                </div>
            @endforeach
        </div>

        {{-- ページネーション --}}
        <div class="d-flex justify-content-center">
            {{ $favorites->links() }}
        </div>
    @endif
</div>
@endsection
