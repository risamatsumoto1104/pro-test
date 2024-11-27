@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
    <div class="register-container">
        <h2 class="register-title">会員登録</h2>
        <form class="register-form" action="">
            <div class="form-group">
                <p class="form-label">ユーザー名</p>
                <input class="form-input" type="text" placeholder="田中 太郎">
                <p class="error-message">エラーメッセージの表示</p>
            </div>
            <div class="form-group">
                <p class="form-label">メールアドレス</p>
                <input class="form-input" type="text">
                <p class="error-message">エラーメッセージの表示</p>
            </div>
            <div class="form-group">
                <p class="form-label">パスワード</p>
                <input class="form-input" type="password" name="password">
                <p class="error-message">エラーメッセージの表示</p>
            </div>
            <div class="form-group">
                <p class="form-label">確認用パスワード</p>
                <input class="form-input" type="password" name="password_confirmation">
                <p class="error-message">エラーメッセージの表示</p>
            </div>
            <div class="form-submit">
                <input class="submit-button" type="submit" value="登録する">
            </div>
        </form>
        <a class="login-link" href="">ログインはこちら</a>
    </div>
@endsection
