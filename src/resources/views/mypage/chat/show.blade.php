@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/chats/show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/rating-modal.css') }}">
@endsection

@section('content')
    {{-- 購入者の場合 --}}
    <div class="buyer-container">
        {{-- サイドバー --}}
        <aside class="sidebar">
            <h2 class="sidebar-title">その他の取引</h2>
            <nav class="sidebar-nav">
                <ul class="trading-item-list">
                    <li class="trading-item">
                        <a class="item-link" href="">商品名</a>
                    </li>
                </ul>
            </nav>
        </aside>

        <div class="right-column-container">
            {{-- コンテンツ --}}
            <article class="content">
                <div class="chat-room-info">
                    {{-- 出品者との取引画面 --}}
                    <div class="chat-room-left-container">
                        <img class="chat-room-user-img" src="{{ asset('profile_images/default-profile.png') }}" alt="ユーザー画像">
                        <h2 class="chat-room-title">「ユーザー名」さんとの取引画面</h2>
                    </div>
                    <div class="chat-room-right-container">
                        <button class="transaction-complete-button">取引を完了する</button>
                    </div>
                </div>

                <div class="trading-item-info">
                    <img class="item-img" src="{{ asset('profile_images/default-profile.png') }}" alt="商品画像">
                    <div class="item-details">
                        <h2 class="item-name">商品名</h2>
                        <p class="item-price">商品価格</p>
                    </div>
                </div>

                <div class="message-container">
                    {{-- 相手メッセージ（出品者） --}}
                    <div class="left-user-message-container">
                        <div class="left-user-details">
                            <img class="left-user-message-img" src="{{ asset('profile_images/default-profile.png') }}"
                                alt="ユーザー名">
                            <p class="left-user-name">ユーザー名</p>
                        </div>
                        <p class="left-user-message">メッセージを表示します</p>
                    </div>
                    {{-- 自分メッセージ（購入者） --}}
                    <div class="right-user-message-container">
                        <div class="right-user-details">
                            <p class="right-user-name">ユーザー名</p>
                            <img class="right-user-message-img" src="{{ asset('profile_images/default-profile.png') }}"
                                alt="ユーザー名">
                        </div>
                        <p class="right-user-message">メッセージを表示します</p>
                        <div class="message-actions">
                            <button class="edit-button">編集</button>
                            <button class="delete-button">削除</button>
                        </div>
                    </div>
                </div>
            </article>

            {{-- フッター --}}
            <footer class="footer">
                <p class="error-message">バリエーションエラーメッセージが表示される</p>
                <form class="message-form" action="" method="post">
                    <input class="message-input" type="text" value="" placeholder="取引メッセージを記入してください">
                    <button class="image-upload-button">
                        画像を追加
                    </button>
                    <img class="send-icon" src="{{ asset('icon_images/inputbutton.png') }}" alt="送信アイコン">
                </form>
            </footer>
        </div>
    </div>

    {{-- 出品者の場合 --}}
    <div class="seller-container">
        {{-- サイドバー --}}
        <aside class="sidebar">
            <h2 class="sidebar-title">その他の取引</h2>
            <nav class="sidebar-nav">
                <ul class="trading-item-list">
                    <li class="trading-item">
                        <a class="item-link" href="">商品名</a>
                    </li>
                </ul>
            </nav>
        </aside>

        <div class="right-column-container">
            {{-- コンテンツ --}}
            <article class="content">
                <div class="chat-room-info">
                    {{-- 購入者との取引画面 --}}
                    <div class="chat-room-left-container">
                        <img class="chat-room-user-img" src="{{ asset('profile_images/default-profile.png') }}" alt="ユーザー画像">
                        <h2 class="chat-room-title">「ユーザー名」さんとの取引画面</h2>
                    </div>
                </div>

                <div class="trading-item-info">
                    <img class="item-img" src="{{ asset('profile_images/default-profile.png') }}" alt="商品画像">
                    <div class="item-details">
                        <h2 class="item-name">商品名</h2>
                        <p class="item-price">商品価格</p>
                    </div>
                </div>

                <div class="message-container">
                    {{-- 相手メッセージ(購入者) --}}
                    <div class="left-user-message-container">
                        <div class="left-user-details">
                            <img class="left-user-message-img" src="{{ asset('profile_images/default-profile.png') }}"
                                alt="ユーザー名">
                            <p class="left-user-name">ユーザー名</p>
                        </div>
                        <p class="left-user-message">メッセージを表示します</p>
                    </div>
                    {{-- 自分メッセージ（出品者） --}}
                    <div class="right-user-message-container">
                        <div class="right-user-details">
                            <p class="right-user-name">ユーザー名</p>
                            <img class="right-user-message-img" src="{{ asset('profile_images/default-profile.png') }}"
                                alt="ユーザー名">
                        </div>
                        <p class="right-user-message">メッセージを表示します</p>
                        <div class="message-actions">
                            <button class="edit-button">編集</button>
                            <button class="delete-button">削除</button>
                        </div>
                    </div>
                </div>
            </article>

            {{-- フッター --}}
            <footer class="footer">
                <p class="error-message">バリエーションエラーメッセージが表示される</p>
                <form class="message-form" action="" method="post">
                    <input class="message-input" type="text" value="" placeholder="取引メッセージを記入してください">
                    <button class="image-upload-button">
                        画像を追加
                    </button>
                    <img class="send-icon" src="{{ asset('icon_images/inputbutton.png') }}" alt="送信アイコン">
                </form>
            </footer>
        </div>
    </div>
@endsection

@section('modals')
    @include('components.rating-modal')
@endsection

@section('scripts')
@endsection
