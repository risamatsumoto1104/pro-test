@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endsection

@section('content')
    <div class="email-verification-container">

        <h1 class="email-verification-title">メール認証が必要です</h1>

        <p class="email-verification-message">
            ご登録いただいたメールアドレスに認証リンクを送信しました。<br>
            メールをご確認の上、認証を完了してください。
        </p>

        <form class="email-verification-form" method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="email-verification-button" type="submit">認証メールを再送信</button>
        </form>

        <form class="logout-form" method="POST" action="{{ url('/logout') }}">
            @csrf
            <button class="logout-button" type="submit">ログアウト</button>
        </form>
    </div>
@endsection
