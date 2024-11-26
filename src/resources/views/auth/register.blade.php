@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
    <div>
        <h2>会員登録</h2>
        <form action="">
            <div>
                <p>ユーザー名</p>
                <input type="text">
            </div>
            <div>
                <p>メールアドレス</p>
                <input type="text">
            </div>
            <div>
                <p>パスワード</p>
                <input type="password" name="password">
            </div>
            <div>
                <p>確認用パスワード</p>
                <input type="password" name="password_confirmation">
            </div>
            <div><input type="submit" value="登録する"></div>
        </form>
        <a href="">ログインはこちら</a>
    </div>
@endsection
