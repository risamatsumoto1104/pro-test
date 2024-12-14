@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profiles/show.css') }}">
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
    <div class="profile-container">
        <div class="profile-info">
            <div class="profile-image-wrapper"
                style="{{ $profile && $profile->profile_image ? '' : 'background-color: #d9d9d9;' }}">
                @if ($profile && $profile->profile_image)
                    <img class="profile-image" src="{{ asset('storage/' . $profile->profile_image) }}" alt="ユーザー画像">
                @endif
            </div>
            <div class="profile-name-wrapper">
                <p class="profile-username">{{ $user->name }}</p>
            </div>
        </div>
        <a class="profile-edit-link" href="{{ url('/mypage/profile') }}">プロフィールを編集</a>
    </div>

    {{-- タブリンク --}}
    <div class="content-list-container">
        <a class="mypage-link {{ $tab === 'sell' ? 'active-link' : '' }}"
            href="{{ route('mypage', ['tab' => 'sell']) }}">出品した商品</a>
        <a class="mypage-link {{ $tab === 'buy' ? 'active-link' : '' }}"
            href="{{ route('mypage', ['tab' => 'buy']) }}">購入した商品</a>
    </div>

    {{-- アイテムリスト --}}
    <div class="items-container">
        @if ($tab === 'sell')
            @if ($soldItems->isEmpty())
                <p class="no-items-message">出品したアイテムはありません。</p>
            @else
                @foreach ($soldItems as $item)
                    <div class="item">
                        <a href="{{ url('item/' . $item->id) }}">
                            <img class="item-image" src="{{ asset('storage/' . $item->image) }}"
                                alt="{{ $item->name }}">
                        </a>
                        @if ($item->status === 'sold')
                            <p class="item-name-sold">Sold</p>
                        @endif
                        <p class="item-name">{{ $item->name }}</p>
                    </div>
                @endforeach
            @endif
        @elseif ($tab === 'buy')
            @if ($boughtItems->isEmpty())
                <p class="no-items-message">購入したアイテムはありません。</p>
            @else
                @foreach ($boughtItems as $item)
                    <div class="item">
                        <a href="{{ url('item/' . $item->id) }}">
                            <img class="item-image" src="{{ asset('storage/' . $item->image) }}"
                                alt="{{ $item->name }}">
                        </a>
                        @if ($item->status === 'sold')
                            <p class="item-name-sold">Sold</p>
                        @endif
                        <p class="item-name">{{ $item->name }}</p>
                    </div>
                @endforeach
            @endif
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('.mypage-link');
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab') || 'sell';

            // タブのアクティブ状態を更新
            links.forEach(link => {
                if (link.href.includes(`tab=${tab}`)) {
                    link.classList.add('active-link');
                } else {
                    link.classList.remove('active-link');
                }
            });
        });
    </script>
@endsection
