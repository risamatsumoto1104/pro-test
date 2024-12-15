@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sells/create.css') }}">
@endsection

@section('content')
    <form action="{{ url('/sell') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="sell-container">
            <h2 class="sell-title">商品出品</h2>

            <div class="sell-image-section">
                <p class="sell-image-label">商品画像</p>
                <div class="sell-image-wrapper">
                    <img class="sell-item-image" src="" alt="商品画像"
                        onerror="this.style.display='none'; this.parentElement.classList.add('profile-image-placeholder');">
                    <label class="sell-image-button" class="sell-image-button" for="sell-image-input">
                        画像を選択する
                    </label>
                    <input class="sell-image-input" name="item_image" id="sell-image-input" type="file">
                </div>
                @error('item_image')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="sell-details-section">
                <h3 class="sell-details-title">商品の詳細</h3>
                <div class="sell-category">
                    <p class="sell-category-label">カテゴリー</p>
                    <div class="sell-category-options">
                        <label class="sell-category-item">
                            <input class="sell-category-input" type="checkbox" name="categories[]" value="ファッション">
                            <span>ファッション</span>
                        </label>
                        <label class="sell-category-item">
                            <input class="sell-category-input" type="checkbox" name="categories[]" value="家電">
                            <span>家電</span>
                        </label>
                        <label class="sell-category-item">
                            <input class="sell-category-input" type="checkbox" name="categories[]" value="インテリア">
                            <span>インテリア</span>
                        </label>
                        <label class="sell-category-item">
                            <input class="sell-category-input" type="checkbox" name="categories[]" value="レディース">
                            <span>レディース</span>
                        </label>
                        <label class="sell-category-item">
                            <input class="sell-category-input" type="checkbox" name="categories[]" value="メンズ">
                            <span>メンズ</span>
                        </label>
                        <label class="sell-category-item">
                            <input class="sell-category-input" type="checkbox" name="categories[]" value="コスメ">
                            <span>コスメ</span>
                        </label>
                        <label class="sell-category-item">
                            <input class="sell-category-input" type="checkbox" name="categories[]" value="本">
                            <span>本</span>
                        </label>
                        <label class="sell-category-item">
                            <input class="sell-category-input" type="checkbox" name="categories[]" value="ゲーム">
                            <span>ゲーム</span>
                        </label>
                        <label class="sell-category-item">
                            <input class="sell-category-input" type="checkbox" name="categories[]" value="スポーツ">
                            <span>スポーツ</span>
                        </label>
                        <label class="sell-category-item">
                            <input class="sell-category-input" type="checkbox" name="categories[]" value="キッチン">
                            <span>キッチン</span>
                        </label>
                        <label class="sell-category-item">
                            <input class="sell-category-input" type="checkbox" name="categories[]" value="ハンドメイド">
                            <span>ハンドメイド</span>
                        </label>
                        <label class="sell-category-item">
                            <input class="sell-category-input" type="checkbox" name="categories[]" value="アクセサリー">
                            <span>アクセサリー</span>
                        </label>
                        <label class="sell-category-item">
                            <input class="sell-category-input" type="checkbox" name="categories[]" value="おもちゃ">
                            <span>おもちゃ</span>
                        </label>
                        <label class="sell-category-item">
                            <input class="sell-category-input" type="checkbox" name="categories[]" value="ベビー・キッズ">
                            <span>ベビー・キッズ</span>
                        </label>
                    </div>
                    @error('category')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div class="sell-condition">
                    <p class="sell-condition-label">商品の状態</p>
                    <div class="sell-condition-select-wrapper">
                        <select class="sell-condition-select" name="condition">
                            <option value="">選択してください</option>
                        </select>
                    </div>
                    @error('condition')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="sell-info-section">
                <h3 class="sell-info-title">商品名と説明</h3>
                <div class="sell-info-item">
                    <p class="sell-info-label">商品名</p>
                    <input class="sell-info-input" name="item_name" type="text">
                    @error('item_name')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div class="sell-info-item">
                    <p class="sell-info-label">商品の説明</p>
                    <textarea class="sell-info-textarea" name="item_description"></textarea>
                    @error('item_description')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div class="sell-info-item">
                    <p class="sell-info-label">販売価格</p>
                    <div class="sell-info-input-wrapper">
                        <input class="sell-info-input" name="price" type="text">
                    </div>
                    @error('price')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-submit">
                    <input class="submit-button" type="submit" value="出品する">
                </div>
            </div>
        </div>
    </form>
@endsection
