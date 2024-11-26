@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
    <div>
        <h2>ログイン</h2>
        <form action="">
            <div>
                <p>ユーザー名/メールアドレス</p>
                <input type="text">
            </div>
            <div>
                <p>パスワード</p>
                <input type="text">
            </div>
            <div><input type="submit" value="ログインする"></div>
        </form>
        <a href="">会計登録はこちら</a>
    </div>
@endsection
