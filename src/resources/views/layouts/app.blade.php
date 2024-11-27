<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Coachtech Fleamarket</title>
    <link rel="stylesheet" href="{{ asset('css/base/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/common.css') }}">
    @yield('css')
</head>

<body class="page-wrapper">
    <header class="header-container">
        <h1 class="header-logo-container">
            <img class="header-logo" src="{{ asset('logo.svg') }}" alt="COACHTECHロゴ">
        </h1>
        @yield('header')
    </header>

    <main class="main-container">
        @yield('content')
    </main>
</body>

</html>
