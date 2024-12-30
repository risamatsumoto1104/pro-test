@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchases/confirm.css') }}">
@endsection

@section('content')
    <div class="success-container">
        <h2>購入が完了しました！</h2>
        <p>ご購入ありがとうございます。</p>
        <a href="{{ route('mypage', ['tab' => 'buy']) }}" class="success-button">購入商品を見る</a>
    </div>
@endsection
