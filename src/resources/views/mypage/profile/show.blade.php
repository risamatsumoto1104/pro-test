@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profiles/show.css') }}">
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
    <div class="profile-container">
        <div class="profile-info">
            <div class="profile-image-wrapper">
                <img class="profile-image" src="" alt="ユーザー画像"
                    onerror="this.style.display='none'; this.parentElement.classList.add('profile-image-placeholder');">
            </div>
            <p class="profile-username">ユーザー名</p>
        </div>
        <a class="profile-edit-link" href="{{ url('/mypage/profile') }}">プロフィールを編集</a>
    </div>
    <div class="content-list-container">
        <a class="mypage-link-sell" href="{{ route('mypage', ['tab' => 'sell']) }}">出品した商品</a>
        <a class="mypage-link-buy" href="{{ route('mypage', ['tab' => 'buy']) }}">購入した商品</a>
    </div>

    {{-- 出品したアイテムの表示 --}}
    @if ($tab == 'sell')
        <h2>出品したアイテム</h2>
        @if ($soldItems->isEmpty())
            <p>出品したアイテムはありません。</p>
        @else
            <ul>
                @foreach ($soldItems as $item)
                    <div class="item">
                        <a href="{{ url('/item/' . 1) }}">
                            <img class="item-image" src="{{ asset('images/HDD.jpg') }}" alt="商品名">
                        </a>
                        <p class="item-name-sold">Sold</p>
                        <p class="item-name">商品名1</p>
                    </div>
                @endforeach
            </ul>
        @endif
    @endif

    {{-- 購入したアイテムの表示 --}}
    @if ($tab == 'buy')
        <h2>購入したアイテム</h2>
        @if ($boughtItems->isEmpty())
            <p>購入したアイテムはありません。</p>
        @else
            <ul>
                @foreach ($boughtItems as $item)
                    <div class="item">
                        <a href="{{ url('/item/' . 1) }}">
                            <img class="item-image" src="{{ asset('images/HDD.jpg') }}" alt="商品名">
                        </a>
                        <p class="item-name-sold">Sold</p>
                        <p class="item-name">商品名1</p>
                    </div>
                @endforeach
            </ul>
        @endif
    @endif
@endsection
