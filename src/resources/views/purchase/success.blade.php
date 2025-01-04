@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchases/success.css') }}">
@endsection

@section('content')
    <div class="purchase-success-container">
        <h2 class="purchase-success-title">Thank You</h2>
        <p class="purchase-success-message">お買い上げありがとうございました！</p>
        <a class="purchase-success-button" href="{{ route('mypage', ['tab' => 'buy']) }}">購入商品を見る</a>
    </div>
@endsection
