@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchases/confirm.css') }}">
@endsection

@section('content')
    <form action="{{ route('purchase.store', ['item_id' => $item->item_id]) }}" method="post">
        @csrf
        <div class="purchase-container">
            {{-- 左列 --}}
            <div class="purchase-left">
                <div class="purchase-item">
                    <div class="purchase-item-info">
                        <img class="purchase-item-image" src="{{ asset('storage/' . $item->item_image) }}"
                            alt="{{ $item->item_name }}">
                        <div class="purchase-item-details">
                            <h2 class="purchase-item-name">{{ $item->item_name }}</h2>
                            <p class="purchase-item-price">{{ number_format($item->price) }}</p>
                        </div>
                    </div>
                    <div class="purchase-payment">
                        <h3 class="purchase-payment-title">支払方法</h3>
                        <div class="purchase-payment-select-container">
                            <select class="purchase-payment-select" name="payment_method">
                                <option value="">選択してください</option>
                                <option value="">コンビニ支払い</option>
                                <option value="">ガード支払い</option>
                            </select>
                        </div>
                        @error('payment_method')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="purchase-address">
                        <div class="purchase-address-header">
                            <h3 class="purchase-address-title">配送先</h3>
                            <a class="purchase-address-link"
                                href="{{ route('purchase.address.edit', ['item_id' => $item->item_id]) }}">変更する</a>
                        </div>
                        {{-- 住所情報が空の場合でもバリデーションメッセージを表示 --}}
                        @if ($address)
                            <p class="purchase-address-postal">〒{{ $address->postal_code }}</p>
                            <p class="purchase-address-main">{{ $address->address }}</p>
                            <p class="purchase-address-building">{{ $address->building }}</p>
                        @else
                            @error('shipping_address')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                </div>

            </div>
            {{-- 右列 --}}
            <div class="purchase-right">
                <table class="purchase-summary">
                    <tr class="purchase-summary-row">
                        <th class="purchase-summary-label">商品代金</th>
                        <td class="purchase-summary-price">{{ number_format($item->price) }}</td>
                    </tr>
                    <tr class="purchase-summary-row">
                        <th class="purchase-summary-label">支払方法</th>
                        <td class="purchase-summary-payment">コンビニ払い</td>
                    </tr>
                </table>
                <input class="purchase-button" type="submit" value="購入する">
            </div>
        </div>
    </form>
@endsection
