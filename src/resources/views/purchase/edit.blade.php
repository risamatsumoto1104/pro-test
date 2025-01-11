@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchases/edit.css') }}">
@endsection

@section('content')
    <div class="purchase-address-container">

        <h2 class="purchase-address-title">住所の変更</h2>

        <form class="purchase-address-form" action="{{ route('purchase.address.update', ['item_id' => $item->item_id]) }}"
            method="POST">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <p class="form-label">郵便番号</p>
                <input class="form-input" name="postal_code" type="text"
                    value="{{ old('postal_code', $profile->postal_code ?? '') }}">
                @error('postal_code')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <p class="form-label">住所</p>
                <input class="form-input" name="address" type="text"
                    value="{{ old('address', $profile->address ?? '') }}">
                @error('address')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <p class="form-label">建物名</p>
                <input class="form-input" name="building" type="text"
                    value="{{ old('building', $profile->building ?? '') }}">
                @error('building')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-submit">
                <input class="submit-button" type="submit" value="更新する">
            </div>
        </form>
    </div>
@endsection
