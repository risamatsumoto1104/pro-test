@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
    <div class="register-container">
        <h2 class="register-title">会員登録</h2>
        <form class="register-form" action="{{ url('/register') }}" method="POST">
            @csrf
            <div class="form-group">
                <p class="form-label">ユーザー名</p>
                <input class="form-input" name="name" type="text" value="{{ old('name') }}">
                @error('name')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <p class="form-label">メールアドレス</p>
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
            <div class="form-group">
                <p class="form-label">確認用パスワード</p>
                <input class="form-input" name="password_confirmation" type="password">
                @error('password_confirmation')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-submit">
                <input class="submit-button" type="submit" value="登録する">
            </div>
        </form>
        <a class="login-link" href="{{ url('/login') }}">ログインはこちら</a>
    </div>
@endsection
