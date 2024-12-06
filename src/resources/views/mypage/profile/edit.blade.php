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
        <div class="profile-image-container">
            <div class="profile-image-wrapper">
                <img class="profile-image" src="" alt="ユーザー画像"
                    onerror="this.style.display='none'; this.parentElement.classList.add('profile-image-placeholder');">
            </div>
            <form class="profile-image-form" action="">
                <label class="profile-image-button" for="profile-image-input">
                    画像を選択する
                </label>
                <input class="profile-image-input" id="profile-image-input" type="file">
            </form>
        </div>
        <form class="purchase-address-form" action="">
            <div class="form-group">
                <p class="form-label">ユーザー名</p>
                <input class="form-input" type="text" placeholder="田中 太郎">
            </div>
            <div class="form-group">
                <p class="form-label">郵便番号</p>
                <input class="form-input" type="text">
            </div>
            <div class="form-group">
                <p class="form-label">住所</p>
                <input class="form-input" type="text">
            </div>
            <div class="form-group">
                <p class="form-label">建物名</p>
                <input class="form-input" type="text">
            </div>
            <div class="form-submit">
                <input class="submit-button" type="submit" value="更新する">
            </div>
        </form>
    </div>
@endsection
