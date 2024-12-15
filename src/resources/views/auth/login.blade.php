@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
    <div class="login-container">
        <h2 class="login-title">ログイン</h2>
        <form class="login-form" action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="form-group">
                <p class="form-label">ユーザー名/メールアドレス</p>
                <input class="form-input" name="email" type="text" value="{{ old('email') }}">
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <p class="form-label">パスワード</p>
                <input class="form-input" name="password" type="password">
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-submit">
                <input class="submit-button" type="submit" value="ログインする">
            </div>
        </form>
        <a class="register-link" href="{{ url('/register') }}">会計登録はこちら</a>
    </div>
@endsection
