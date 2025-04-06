@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchases/confirm.css') }}">
@endsection

@section('content')
    <form action="{{ route('purchase.store', ['item_id' => $item->item_id]) }}" method="post">
        @csrf
        <div class="purchase-container">

            <!-- 隠しフィールドとして buyer_user_id を送信 -->
            <input type="hidden" name="buyer_user_id" value="{{ auth()->user()->user_id }}">

            {{-- 左列 --}}
            <div class="purchase-left">
                <div class="purchase-item">
                    <div class="purchase-item-info">
                        <img class="purchase-item-image"
                            src="{{ file_exists(public_path('item_images/' . $item->item_image))
                                ? asset('item_images/' . $item->item_image)
                                : asset('storage/' . $item->item_image) }}"
                            alt="{{ $item->item_name }}">
                        <div class="purchase-item-details">
                            <h2 class="purchase-item-name">{{ $item->item_name }}</h2>
                            <p class="purchase-item-price">{{ number_format($item->price) }}</p>
                        </div>
                    </div>

                    <div class="purchase-payment">
                        <h3 class="purchase-payment-title">支払方法</h3>
                        <div class="purchase-payment-select-container">
                            <select class="purchase-payment-select" name="payment_method" id="payment-method-select">
                                <option value="" disabled selected>選択してください</option>
                                <option value="コンビニ支払い">コンビニ支払い</option>
                                <option value="カード支払い">カード支払い</option>
                            </select>
                            </select>
                        </div>
                        @error('payment_method')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 隠しフィールドとして address_id を送信 -->
                    <input type="hidden" name="address_id" value="{{ $address->address_id ?? '' }}">

                    <div class="purchase-address">
                        <div class="purchase-address-header">
                            <h3 class="purchase-address-title">配送先</h3>
                            <a class="purchase-address-link"
                                href="{{ route('purchase.address.edit', ['item_id' => $item->item_id]) }}">
                                変更する
                            </a>
                        </div>
                        {{-- 住所情報が空の場合でもバリデーションメッセージを表示 --}}
                        <p class="purchase-address-postal">〒{{ $address->postal_code }}</p>
                        <p class="purchase-address-main">{{ $address->address }}</p>
                        <p class="purchase-address-building">{{ $address->building }}</p>
                        @error('address_id')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
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
                        <td class="purchase-summary-payment" id="summary-payment-method">選択してください</td>
                    </tr>
                </table>
                <input class="purchase-button" type="submit" value="購入する">
                <!-- エラーメッセージ表示 -->
                @if ($errors->has('error'))
                    <p class="submit-error-message">{{ $errors->first('error') }}</p>
                @endif
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentSelect = document.getElementById('payment-method-select');
            const summaryPayment = document.getElementById('summary-payment-method');
            const storedPaymentMethod = localStorage.getItem('payment_method');

            // ページロード時にローカルストレージから取得した値を設定
            if (storedPaymentMethod) {
                paymentSelect.value = storedPaymentMethod;
                summaryPayment.textContent = storedPaymentMethod; // 小計にも反映
            } else {
                paymentSelect.value = ''; // ローカルストレージに値がなければ初期値を設定
                summaryPayment.textContent = '選択してください'; // 初期状態に戻す
            }

            // セレクトボックスの値が変更された場合、ローカルストレージに保存
            paymentSelect.addEventListener('change', function() {
                const selectedPayment = paymentSelect.value;
                localStorage.setItem('payment_method', selectedPayment);
                summaryPayment.textContent = selectedPayment; // 小計にも反映
            });

            // ログアウトフォームの送信前に localStorage をクリア
            const logoutForm = document.querySelector('form[action="/logout"]');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function() {
                    localStorage.removeItem('payment_method'); // 支払い方法の選択情報を削除
                });
            }
        });
    </script>
@endsection
