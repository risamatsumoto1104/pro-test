@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('header')
    <div class="header">
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
                    <a class="nav-link" href="{{ url('/sell') }}">出品</a>
                </li>
            </ul>
        </nav>
    </div>
@endsection

@section('content')
    <div class="content-list-container">
        <p class="recommend-title">おすすめ</p>
        <a class="mylist-link" href="{{ url('/?tab=mylist') }}">マイリスト</a>
    </div>
    <form class="items-form" action="">
        <div class="item">
            <img class="item-image" src="{{ asset('images/HDD.jpg') }}" alt="商品名">
            <p class="item-name sold">Sold</p>
            <a class="item-name" href="{{ url('/item/' . 1) }}">商品名1</a>
        </div>
        <div class="item">
            <img class="item-image" src="{{ asset('images/HDD.jpg') }}" alt="商品名">
            <p class="item-name sold">Sold</p>
            <p class="item-name">商品名2</p>
        </div>
        <div class="item">
            <img class="item-image" src="{{ asset('images/HDD.jpg') }}" alt="商品名">
            <p class="item-name sold">Sold</p>
            <p class="item-name">商品名3</p>
        </div>
        <div class="item">
            <img class="item-image" src="{{ asset('images/HDD.jpg') }}" alt="商品名">
            <p class="item-name sold">Sold</p>
            <p class="item-name">商品名4</p>
        </div>
        <div class="item">
            <img class="item-image" src="{{ asset('images/HDD.jpg') }}" alt="商品名">
            <p class="item-name sold">Sold</p>
            <p class="item-name">商品名5</p>
        </div>
    </form>
@endsection
