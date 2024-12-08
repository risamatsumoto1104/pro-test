@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('header')
    <form class="header-search-form" action="">
        <input class="search-input" type="text" placeholder="なにをお探しですか？">
    </form>
    <nav class="header-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <form action="/logout" method="post">
                    @csrf
                    <button class="nav-link" type="submit">ログアウト</button>
                </form>
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
    <div class="content-list-container">
        <p class="recommend-title">おすすめ</p>
        <a class="mylist-link" href="{{ url('/?tab=mylist') }}">マイリスト</a>
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
