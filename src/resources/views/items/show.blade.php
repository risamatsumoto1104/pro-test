@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('header')
    <form action="">
        <input type="text" placeholder="なにをお探しですか？">
    </form>
    <nav>
        <ul>
            <li>
                <a href="{{ url('/login') }}">ログアウト</a>
            </li>
            <li>
                <a href="{{ url('/mypage') }}">マイページ</a>
            </li>
            <li>
                <a href="{{ url('/sell') }}">出品</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <div>
        <p>おすすめ</p>
        <a href="{{ url('/?tab=mylist') }}"></a>
    </div>
    <form action="">
        <div>
            <img src="{{ asset('images/HDD.jpg') }}" alt="商品名">
            <p>商品名1</p>
        </div>
        <div>
            <img src="{{ asset('images/HDD.jpg') }}" alt="商品名">
            <p>商品名2</p>
        </div>
        <div>
            <img src="{{ asset('images/HDD.jpg') }}" alt="商品名">
            <p>商品名3</p>
        </div>
        <div>
            <img src="{{ asset('images/HDD.jpg') }}" alt="商品名">
            <p>商品名4</p>
        </div>
        <div>
            <img src="{{ asset('images/HDD.jpg') }}" alt="商品名">
            <p>商品名5</p>
        </div>
    </form>
@endsection
