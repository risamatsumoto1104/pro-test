@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
    <div class="login-container">
        <h2 class="login-title">ログイン</h2>
        <form class="login-form" action="">
            <div class="form-group">
                <p class="form-label">ユーザー名/メールアドレス</p>
                <input class="form-input" type="text" placeholder="田中 太郎">
                <p class="error-message">エラーメッセージの表示</p>
            </div>
            <div class="form-group">
                <p class="form-label">パスワード</p>
                <input class="form-input" type="text">
                <p class="error-message">エラーメッセージの表示</p>
            </div>
            <div class="form-submit">
                <input class="submit-button" type="submit" value="ログインする">
            </div>
        </form>
        <a class="register-link" href="">会計登録はこちら</a>
    </div>
@endsection
