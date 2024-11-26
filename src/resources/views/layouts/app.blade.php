<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Coachtech Fleamarket</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header>
        <div>
            <img src="{{ asset('logo.svg') }}" alt="COACHTECHロゴ">
        </div>
        @yield('header')
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>
