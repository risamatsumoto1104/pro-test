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
                    <!-- プロフィール画像が登録されている場合は表示、なければデフォルト画像を表示 -->
                    <img class="profile-image" id="profile-image"
                        src="{{ $profile && $profile->profile_image
                            ? (file_exists(storage_path('storage/' . $profile->profile_image))
                                ? asset('storage/' . $profile->profile_image)
                                : asset('profile_images/' . $profile->profile_image))
                            : asset('profile_images/default-profile.png') }}"
                        alt="ユーザー画像">
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
            var profileImg = document.querySelector('#profile-image'); // プロフィール画像
            var reader = new FileReader();

            // 画像が選択されていれば画像表示エレメントを作成
            if (input.files && input.files[0]) {
                reader.onload = function(e) {
                    // プロフィール画像を新しい画像に更新
                    profileImg.src = e.target.result; // 選択した画像を表示
                };
                reader.readAsDataURL(input.files[0]); // 画像をデータURLとして読み込む
            } else {
                // 画像が選択されていない場合はデフォルト画像を表示
                profileImg.src = "{{ asset('profile_images/default-profile.png') }}";
            }
        }
    </script>
@endsection
