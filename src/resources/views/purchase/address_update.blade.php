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
    <p>address_updateページ</p>
@endsection
