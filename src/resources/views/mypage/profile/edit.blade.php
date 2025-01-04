@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profiles/edit.css') }}">
@endsection

@section('content')
    <form class="purchase-address-form" action="{{ route('mypage.profile.update') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="purchase-address-container">
            <h2 class="purchase-address-title">プロフィール設定</h2>
            <div class="profile-image-container">
                <div class="profile-image-wrapper">
                    <img class="profile-image" src="">
                    <input class="profile-image-input" name="profile_image" id="profile-image-input" type="file"
                        onchange="previewImage(event)">
                </div>
                <label class="profile-image-button" for="profile-image-input">
                    画像を選択する
                </label>
                @error('profile_image')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <p class="form-label">ユーザー名</p>
                <input class="form-input" name="name" type="text" value="{{ old('name', $user->name ?? '') }}">
                @error('name')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
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
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        function previewImage(event) {
            var input = event.target; // 画像選択されたinput
            var wrapper = document.querySelector('.profile-image-wrapper'); // 画像のラッパー
            var imgElement = document.querySelector('.profile-image'); // 画像表示の要素

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    imgElement.src = e.target.result; // 選択された画像を表示
                    wrapper.classList.add('image-selected'); // ラッパーにimage-selectedクラスを追加
                };
                reader.readAsDataURL(input.files[0]); // 画像をデータURLとして読み込む
            }
        }
    </script>
@endsection
