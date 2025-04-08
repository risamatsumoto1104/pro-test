<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>商品が評価されました</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f9f9f9;
            color: #333;
            padding: 20px;
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #ddd;
            text-align: center;
        }

        a {
            color: #3490dc;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>{{ $otherUser->name }}さん、こんにちは！</h2>
        <p>あなたが出品した「{{ $item->item_name }}」について、購入者により評価されました。</p>
        <p>あなたへの評価 ： ★{{ $rating->rating }}</p>
        <p>マイページの取引中の商品より購入者への評価を行って下さい。</p>
        <p>
            <a href="{{ route('mypage') }}">▶ マイページを開く</a>
        </p>
    </div>
</body>

</html>
