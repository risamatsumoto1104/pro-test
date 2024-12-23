<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Coachtech Fleamarket</title>
    <link rel="stylesheet" href="{{ asset('css/base/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/common.css') }}">
    @yield('css')
</head>

<body class="page-wrapper">
    <header class="header-container">
        <h1 class="header-logo-container">
            <a href="{{ route('home') }}">
                <img class="header-logo" src="{{ asset('logo.svg') }}" alt="COACHTECHロゴ">
            </a>
        </h1>
        <form class="header-search-form" action="{{ route('item.search') }}" method="get">
            <input class="search-input" type="text" name="keyword" value="{{ old('keyword', $keyword ?? '') }}"
                placeholder="なにをお探しですか？">
            <input type="hidden" name="tab" value="{{ $tab ?? '' }}" />
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
    </header>

    <main class="main-container">
        @yield('content')
    </main>

    @yield('scripts')
</body>

</html>
