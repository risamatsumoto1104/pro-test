@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('header')
    <form class="header-search-form" action="">
        <input class="search-input" type="text" placeholder="なにをお探しですか？">
    </form>
    <nav class="header-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/login') }}">ログアウト</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/mypage') }}">マイページ</a>
            </li>
            <li class="nav-item">
                <a class="nav-link-sell" href="{{ url('/sell') }}">出品</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <div class="item-content">
        <article class="item-image">
            <img class="item-image-img" src="{{ asset('images/コーヒーミル.jpg') }}" alt="商品名">
        </article>

        <aside class="item-details">
            <div class="item-overview-container">
                <h2 class="item-overview-title">商品名がここに入る</h2>
                <p class="item-overview-brand">ブランド名</p>
                <p class="item-overview-price">47,000</p>
                <div class="item-overview-icon-container">
                    <div class="item-overview-like">
                        <img class="item-overview-like-icon" src="{{ asset('images/星アイコン8.png') }}" alt="星アイコン">
                        <p class="item-overview-like-count">3</p>
                    </div>
                    <div class="item-overview-comment">
                        <img class="item-overview-comment-icon" src="{{ asset('images/ふきだしのアイコン.png') }}" alt="ふきだしアイコン">
                        <p class="item-overview-comment-count">1</p>
                    </div>
                </div>
            </div>
            <form class="item-purchase-submit">
                <input class="item-purchase-button" type="submit" value="購入手続きへ">
            </form>

            <div class="item-description-container">
                <h3 class="item-description-title">商品説明</h3>
                <p class="item-description-text">
                    カラー：グレー<br>
                    <br>
                    新品<br>
                    商品の状態は良好です。傷もありません。<br>
                    <br>
                    購入後、即発送いたします。
                </p>
            </div>

            <div class="item-info-container">
                <h3 class="item-info-title">商品の情報</h3>
                <div class="item-info-category">
                    <h4 class="item-info-category-title">カテゴリー</h4>
                    <div class="item-info-category-list">
                        <p class="item-info-category-item">ファッション</p>
                        <p class="item-info-category-item">家電</p>
                        <p class="item-info-category-item">インテリア</p>
                        <p class="item-info-category-item">レディース</p>
                        <p class="item-info-category-item">メンズ</p>
                        <p class="item-info-category-item">コスメ</p>
                        <p class="item-info-category-item">本</p>
                        <p class="item-info-category-item">ゲーム</p>
                    </div>
                </div>
                <div class="item-info-condition">
                    <h4 class="item-info-condition-title">商品の状態</h4>
                    <p class="item-info-condition-value">良好</p>
                </div>
            </div>

            <div class="item-comment-container">
                <p class="item-comment-count">コメント（1）</p>
                <div class="item-comment-user">
                    <img class="item-comment-user-img" src="{{ asset('images/ショルダーバッグ.jpg') }}" alt="プロフィール画像">
                    <p class="item-comment-user-name">ユーザー名</p>
                </div>
                <form class="item-comment-form" action="">
                    <textarea class="item-comment-text" name="" id="" cols="" rows="">こちらにコメントが入ります。</textarea>
                    <div class="item-comment-input">
                        <p class="item-comment-label">商品へのコメント</p>
                        <textarea class="item-comment-input-text" name="" id="" cols="" rows=""></textarea>
                    </div>
                </form>
                <form class="item-comment-submit">
                    <input class="item-comment-submit-button" type="submit" value="コメントを送信する">
                </form>
            </div>
        </aside>
    </div>
@endsection
