@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('header')
    <form class="header-search-form" action="">
        <input class="search-input" type="text" placeholder="なにをお探しですか？">
    </form>
    <nav class="header-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <form action="/logout" method="post">
                    @csrf
                    <button class="nav-link" type="submit">ログアウト</button>
                </form>
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
    <div class="content-list-container">
        <a class="recommend-link" href="{{ route('home', ['tab' => 'recommend']) }}">おすすめ</a>
        <a class="mylist-link" href="{{ route('home', ['tab' => 'mylist']) }}">マイリスト</a>
    </div>

    <div class="items-container">
        @if ($tab === 'recommend')
            {{-- おすすめを表示 --}}
            @foreach ($items as $item)
                <div class="item">
                    <a href="{{ url('item/' . $item->item_id) }}">
                        <img class="item-image" src="{{ asset('storage/' . $item->item_image) }}"
                            alt="{{ $item->item_name }}">
                    </a>
                    @if ($item->status === 'sold')
                        <p class="item-name-sold">Sold</p>
                    @endif
                    <p class="item-name">{{ $item->item_name }}</p>
                </div>
            @endforeach
        @elseif ($tab === 'mylist' && auth()->check())
            {{-- マイリストを表示 --}}
            @if ($items->isNotEmpty())
                @foreach ($items as $item)
                    <div class="item">
                        <a href="{{ url('item/' . $item->item_id) }}">
                            <img class="item-image" src="{{ asset('storage/' . $item->item_image) }}"
                                alt="{{ $item->item_name }}">
                        </a>
                        @if ($item->status === 'sold')
                            <p class="item-name-sold">Sold</p>
                        @endif
                        <p class="item-name">{{ $item->item_name }}</p>
                    </div>
                @endforeach
            @else
                <p class="no-items-message">「いいね」した商品はありません。</p>
            @endif
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const recommendLink = document.querySelector('.recommend-link');
            const mylistLink = document.querySelector('.mylist-link');

            // URLのクエリパラメータに基づいてアクティブリンクを設定
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('tab') === 'mylist') {
                mylistLink.classList.add('active-link');
                recommendLink.classList.remove('active-link');
            } else {
                recommendLink.classList.add('active-link');
                mylistLink.classList.remove('active-link');
            }
        });
    </script>
@endsection
