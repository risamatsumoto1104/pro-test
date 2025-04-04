@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profiles/show.css') }}">
@endsection

@section('content')
    <div class="profile-container">
        <div class="profile-info">
            <div class="profile-image-wrapper">
                <!-- プロフィール画像が登録されている場合は表示、なければデフォルト画像を表示 -->
                <img class="profile-image" id="profile-image"
                    src="{{ $profile && $profile->profile_image
                        ? asset('storage/' . $profile->profile_image)
                        : asset('profile_images/default-profile.png') }}"
                    alt="ユーザー画像">
            </div>
            <div class="profile-name-wrapper">
                <p class="profile-username">{{ $user->name }}</p>
                <div class="rating-modal-stars">
                    <p class="rating-modal-star">&#9733;</p>
                    <p class="rating-modal-star">&#9733;</p>
                    <p class="rating-modal-star">&#9733;</p>
                    <p class="rating-modal-star">&#9733;</p>
                    <p class="rating-modal-star">&#9733;</p>
                </div>
            </div>
        </div>
        <a class="profile-edit-link" href="{{ url('/mypage/profile') }}">プロフィールを編集</a>
    </div>

    {{-- タブリンク --}}
    <div class="content-list-container">
        <a class="sell-link" href="{{ route('mypage', ['tab' => 'sell']) }}">出品した商品</a>
        <a class="buy-link" href="{{ route('mypage', ['tab' => 'buy']) }}">購入した商品</a>
        <div class="trading-link-container">
            <a class="trading-link" href="{{ route('mypage', ['tab' => 'trading']) }}">取引中の商品</a>
            <p class="notification-icon">1</p>
        </div>
    </div>

    {{-- アイテムリスト --}}
    <div class="items-container">
        @if ($tab === 'sell')
            {{-- 出品した商品一覧 --}}
            @if ($soldItems->isEmpty())
                <p class="no-items-message">出品したアイテムはありません。</p>
            @else
                @foreach ($soldItems as $item)
                    <div class="item">
                        <a href="{{ url('item/' . $item->item_id) }}">
                            <img class="item-image"
                                src="{{ file_exists(public_path('item_images/' . $item->item_image))
                                    ? asset('item_images/' . $item->item_image)
                                    : asset('storage/' . $item->item_image) }}"
                                alt="{{ $item->item_name }}">
                        </a>
                        @if ($item->status === 'sold')
                            <a href="{{ url('item/' . $item->item_id) }}">
                                <p class="item-name-sold">Sold</p>
                            </a>
                        @endif
                        <p class="item-name">{{ $item->item_name }}</p>
                    </div>
                @endforeach
            @endif
        @elseif ($tab === 'buy')
            {{-- 購入した商品一覧 --}}
            @if ($boughtItems->isEmpty())
                <p class="no-items-message">購入したアイテムはありません。</p>
            @else
                @foreach ($boughtItems as $item)
                    <div class="item">
                        <a href="{{ url('item/' . $item->item_id) }}">
                            <img class="item-image"
                                src="{{ file_exists(public_path('item_images/' . $item->item_image))
                                    ? asset('item_images/' . $item->item_image)
                                    : asset('storage/' . $item->item_image) }}"
                                alt="{{ $item->item_name }}">
                        </a>
                        @if ($item->status === 'sold')
                            <a href="{{ url('item/' . $item->item_id) }}">
                                <p class="item-name-sold">Sold</p>
                            </a>
                        @endif
                        <p class="item-name">{{ $item->item_name }}</p>
                    </div>
                @endforeach
            @endif
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sellLink = document.querySelector('.sell-link');
            const buyLink = document.querySelector('.buy-link');

            // URLのクエリパラメータに基づいてアクティブリンクを設定
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('tab') === 'buy') {
                buyLink.classList.add('active-link');
                sellLink.classList.remove('active-link');
            } else {
                sellLink.classList.add('active-link');
                buyLink.classList.remove('active-link');
            }
        });
    </script>
@endsection
