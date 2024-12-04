@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profiles/edit.css') }}">
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
    <div class="purchase-address-container">
        <h2 class="purchase-address-title">プロフィール設定</h2>
        <form class="purchase-address-form" action="">
            <div class="form-group">
                <p class="form-label">ユーザー名</p>
                <input class="form-input" type="text" placeholder="田中 太郎">
                <p class="error-message">エラーメッセージの表示</p>
            </div>
            <div class="form-group">
                <p class="form-label">郵便番号</p>
                <input class="form-input" type="text" placeholder="田中 太郎">
                <p class="error-message">エラーメッセージの表示</p>
            </div>
            <div class="form-group">
                <p class="form-label">住所</p>
                <input class="form-input" type="text">
                <p class="error-message">エラーメッセージの表示</p>
            </div>
            <div class="form-group">
                <p class="form-label">建物名</p>
                <input class="form-input" type="text">
                <p class="error-message">エラーメッセージの表示</p>
            </div>
            <div class="form-submit">
                <input class="submit-button" type="submit" value="更新する">
            </div>
        </form>
    @endsection
