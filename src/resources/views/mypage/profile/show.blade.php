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
            <img class="profile-image" src="{{ asset('images/コーヒーミル.jpg') }}" alt="ユーザー画像">
            <p class="profile-username">ユーザー名</p>
        </div>
        <a class="profile-edit-link" href="{{ url('/mypage/profile') }}">プロフィールを編集</a>
    </div>
    <div class="content-list-container">
        <a class="mypage-link-sell" href="{{ url('/mypage?tab=sell') }}">出品した商品</a>
        <a class="mypage-link-buy" href="{{ url('/mypage?tab=buy') }}">購入した商品</a>
    </div>
    <form class="items-form" action="">
        <div class="item">
            <a href="{{ url('/item/' . 1) }}">
                <img class="item-image" src="{{ asset('images/HDD.jpg') }}" alt="商品名">
            </a>
            <p class="item-name-sold">Sold</p>
            <p class="item-name">商品名1</p>
        </div>
        <div class="item">
            <a href="{{ url('/item/' . 1) }}">
                <img class="item-image" src="{{ asset('images/HDD.jpg') }}" alt="商品名">
            </a>
            <p class="item-name-sold">Sold</p>
            <p class="item-name">商品名1</p>
        </div>
        <div class="item">
            <a href="{{ url('/item/' . 1) }}">
                <img class="item-image" src="{{ asset('images/HDD.jpg') }}" alt="商品名">
            </a>
            <p class="item-name-sold">Sold</p>
            <p class="item-name">商品名1</p>
        </div>
        <div class="item">
            <a href="{{ url('/item/' . 1) }}">
                <img class="item-image" src="{{ asset('images/HDD.jpg') }}" alt="商品名">
            </a>
            <p class="item-name-sold">Sold</p>
            <p class="item-name">商品名1</p>
        </div>
        <div class="item">
            <a href="{{ url('/item/' . 1) }}">
                <img class="item-image" src="{{ asset('images/HDD.jpg') }}" alt="商品名">
            </a>
            <p class="item-name-sold">Sold</p>
            <p class="item-name">商品名1</p>
        </div>
    </form>
@endsection
