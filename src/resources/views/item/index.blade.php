@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
    <div class="content-list-container">
        <a class="recommend-link" href="{{ route('home', ['tab' => 'recommend', 'keyword' => request('keyword')]) }}">おすすめ</a>
        <a class="mylist-link" href="{{ route('home', ['tab' => 'mylist', 'keyword' => request('keyword')]) }}">マイリスト</a>
    </div>

    <div class="items-container">
        {{-- タブがrecommendの場合 --}}
        @if ($tab === 'recommend')
            {{-- おすすめを表示 --}}
            @foreach ($items ?? [] as $item)
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
        @elseif ($tab === 'mylist')
            @if (auth()->check())
                {{-- ログイン済みの場合、マイリストを表示 --}}
                @if (($items ?? collect())->isNotEmpty())
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
                    {{-- 「いいね」した商品がない場合 --}}
                    <p class="no-likes-message">「いいね」した商品はありません。</p>
                @endif
            @else
                {{-- ログインしていない場合 --}}
                <p class="no-items-message">ログインしてください。</p>
            @endif
        @endif
    </div>
    <div class="search-results-container">
        {{-- 検索結果がある場合 --}}
        @if (isset($searchResults))
            <style>
                .no-likes-message {
                    display: none;
                }
            </style>

            @if ($searchResults->isNotEmpty())
                @foreach ($searchResults as $item)
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
            @elseif ($searchResults->isEmpty())
                {{-- 検索結果が空の場合 --}}
                <p class="no-items-message">該当する商品がありません。</p>
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
