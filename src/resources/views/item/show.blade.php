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
        <article>
            <img src="{{ asset('images/コーヒーミル.jpg') }}" alt="商品名">
        </article>

        <aside>
            <h2>商品名がここに入る</h2>
            <p>ブランド名</p>
            <p>￥47,000（税込）</p>
            <div>
                <div>
                    <img src="{{ asset('images/星アイコン8.png') }}" alt="星アイコン">
                    <p>3</p>
                </div>
                <div>
                    <img src="{{ asset('images/ふきだしのアイコン.png') }}" alt="ふきだしアイコン">
                    <p>1</p>
                </div>
            </div>
        </aside>
    </div>
@endsection
