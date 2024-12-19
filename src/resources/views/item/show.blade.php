@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')
    <div class="item-content">
        {{-- 左列 --}}
        <div class="item-image">
            <img class="item-image-img" src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->item_name }}">
        </div>

        {{-- 右列 --}}
        <div class="item-details">
            <div class="item-overview-container">
                <h2 class="item-overview-title">{{ $item->item_name }}</h2>
                <p class="item-overview-brand">{{ $item->brand_name }}</p>
                <p class="item-overview-price">{{ number_format($item->price) }}</p>
                <div class="item-overview-icon-container">
                    <div class="item-overview-like">
                        <img class="item-overview-like-icon" id="like-icon-{{ $item->item_id }}"
                            data-item-id="{{ $item->item_id }}"
                            src="{{ asset($liked ? 'storage/星アイコン_liked.png' : 'storage/星アイコン8.png') }}" alt="星アイコン">
                        <p class="item-overview-like-count">{{ $item->item_likes_count }}</p>
                    </div>
                    <div class="item-overview-comment">
                        <img class="item-overview-comment-icon" src="{{ asset('storage/ふきだしのアイコン.png') }}" alt="ふきだしアイコン">
                        <p class="item-overview-comment-count">{{ $item->comments_count }}</p>
                    </div>
                </div>
            </div>
            <form class="item-purchase-submit" action="{{ route('item.purchase', ['item_id' => $item->item_id]) }}"
                method="GET">
                <input class="item-purchase-button" type="submit" value="購入手続きへ">
            </form>

            <div class="item-description-container">
                <h3 class="item-description-title">商品説明</h3>
                <p class="item-description-text">{{ $item->description }}</p>
            </div>

            <div class="item-info-container">
                <h3 class="item-info-title">商品の情報</h3>
                <div class="item-info-category">
                    <h4 class="item-info-category-title">カテゴリー</h4>
                    <div class="item-info-category-list">
                        @foreach ($item->categories as $category)
                            <p class="item-info-category-item">{{ $category->content }}</p>
                        @endforeach
                    </div>
                </div>
                <div class="item-info-condition">
                    <h4 class="item-info-condition-title">商品の状態</h4>
                    <p class="item-info-condition-value">{{ $item->condition }}</p>
                </div>
            </div>

            <div class="item-comment-container">
                {{-- コメント数を表示 --}}
                <p class="item-comment-count">コメント（{{ $item->comments_count }}）</p>
                {{-- コメント一覧 --}}
                @foreach ($item->comments as $comment)
                    <div class="item-comment-user">
                        <div class="item-comment-image-wrapper">
                            <!-- ユーザー画像の表示（画像がない場合はプレースホルダー表示） -->
                            <img class="item-comment-user-img"
                                src="{{ asset('storage/' . $comment->user->profile_image) }}" alt="ユーザー画像"
                                onerror="this.style.display='none'; this.parentElement.classList.add('item-comment-user-img-placeholder');">
                        </div>
                        <p class="item-comment-user-name">{{ $comment->user->name }}</p> <!-- ユーザー名表示 -->
                    </div>
                    <p class="item-comment-text">{{ $comment->content }}</p> <!-- コメント内容表示 -->
                @endforeach
                {{-- コメント送信 --}}
                @auth
                    <form class="item-comment-form" action="{{ route('item.comment', ['item_id' => $item->item_id]) }}"
                        method="post">
                        @csrf
                        <div class="item-comment-input">
                            <p class="item-comment-label">商品へのコメント</p>
                            <textarea class="item-comment-input-text" name="comment"></textarea>
                        </div>
                        @error('comment')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                        <input class="item-comment-submit-button" type="submit" value="コメントを送信する">
                    </form>
                @else
                    <p class="item-comment-login-notice">コメントを送信するにはログインが必要です。</p>
                @endauth
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // すべてのアイコンに対してクリックイベントを設定
        document.querySelectorAll('.item-overview-like-icon').forEach(icon => {
            icon.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                const liked = this.getAttribute('data-liked') === 'true';

                // いいねの状態をトグルするリクエストを送信
                fetch(`/item/${itemId}/like`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                        },
                        body: JSON.stringify({
                            liked: liked
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // アイコンの状態やいいね数を更新
                        const likeIcon = document.getElementById(`like-icon-${itemId}`);
                        likeIcon.src = data.liked ? '/storage/星アイコン_liked.png' : '/storage/星アイコン8.png';

                        document.querySelector('.item-overview-like-count').textContent =
                            `${data.likeCount}`;

                        // アイコンのデータ属性を更新
                        likeIcon.setAttribute('data-liked', data.liked);
                    })
                    .catch(error => {
                        console.error('いいね処理に失敗しました:', error);
                    });
            });
        });
    </script>
@endsection
