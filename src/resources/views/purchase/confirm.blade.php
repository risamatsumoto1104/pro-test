@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchases/confirm.css') }}">
@endsection

@section('header')
    <form class="header-search-form" action="">
        <input class="search-input" type="text" placeholder="なにをお探しですか？">
    </form>
    <nav class="header-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/login') }}">ログアウト</a>
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
    <div class="purchase-container">
        {{-- 左列 --}}
        <div class="purchase-left">
            <div class="purchase-item">
                <div class="purchase-item-info">
                    <img class="purchase-item-image" src="{{ asset('images/コーヒーミル.jpg') }}" alt="商品名">
                    <div class="purchase-item-details">
                        <h2 class="purchase-item-name">商品名</h2>
                        <p class="purchase-item-price">47,000</p>
                    </div>
                </div>
                <div class="purchase-payment">
                    <h3 class="purchase-payment-title">支払方法</h3>
                    <div class="purchase-payment-select-container">
                        <select class="purchase-payment-select" name="">
                            <option value="">選択してください</option>
                        </select>
                    </div>
                    <p class="error-message">エラーメッセージの表示</p>
                </div>
                <div class="purchase-address">
                    <div class="purchase-address-header">
                        <h3 class="purchase-address-title">配送先</h3>
                        <a class="purchase-address-link" href="{{ url('/purchase/address/' . 1) }}">変更する</a>
                    </div>
                    <p class="purchase-address-postal">郵便番号を表示する</p>
                    <p class="error-message">エラーメッセージの表示</p>
                    <p class="purchase-address-main">住所を表示する</p>
                    <p class="error-message">エラーメッセージの表示</p>
                    <p class="purchase-address-building">建物名を表示する</p>
                    <p class="error-message">エラーメッセージの表示</p>
                </div>
            </div>

        </div>
        {{-- 右列 --}}
        <div class="purchase-right">
            <table class="purchase-summary">
                <tr class="purchase-summary-row">
                    <th class="purchase-summary-label">商品代金</th>
                    <td class="purchase-summary-price">47,000</td>
                </tr>
                <tr class="purchase-summary-row">
                    <th class="purchase-summary-label">支払方法</th>
                    <td class="purchase-summary-payment">コンビニ払い</td>
                </tr>
            </table>
            <form class="purchase-form">
                <input class="purchase-button" type="submit" value="購入する">
            </form>
        </div>
    </div>
@endsection
