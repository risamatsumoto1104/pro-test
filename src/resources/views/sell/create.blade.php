@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sells/create.css') }}">
@endsection

@section('content')
    <form action="{{ route('item.sell.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <!-- 隠しフィールドとして seller_user_id を送信 -->
        <input type="hidden" name="seller_user_id" value="{{ auth()->user()->user_id ?? '' }}">
        <!-- 隠しフィールドとして brand_name を送信 -->
        <input type="hidden" name="brand_name" value="{{ old('brand_name') ?? '' }}">

        <div class="sell-container">
            <h2 class="sell-title">商品出品</h2>

            <div class="sell-image-section">
                <p class="sell-image-label">商品画像</p>
                <div class="sell-image-wrapper">
                    <img class="sell-item-image" id="preview" src="" alt="商品画像" style="display: none;">
                    <label class="sell-image-button" for="sell-image-input">画像を選択する</label>
                    <input class="sell-image-input" name="item_image" id="sell-image-input" type="file"
                        onchange="previewImage(event)">
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
                        @foreach ($categories as $category)
                            <label class="sell-category-item">
                                <input class="sell-category-input" type="checkbox" name="categories[]"
                                    value="{{ $category->category_id }}">
                                <span>{{ $category->content }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('categories')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sell-condition">
                    <p class="sell-condition-label">商品の状態</p>
                    <div class="sell-condition-select-wrapper">
                        <select class="sell-condition-select" name="condition">
                            <option value="" selected>選択してください</option>
                            <option value="良好">良好</option>
                            <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                            <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                            <option value="状態が悪い">状態が悪い</option>
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
                    <input class="sell-info-input" name="item_name" type="text" value="{{ old('item_name') }}">
                    @error('item_name')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sell-info-item">
                    <p class="sell-info-label">商品の説明</p>
                    <textarea class="sell-info-textarea" name="description">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sell-info-item">
                    <p class="sell-info-label">販売価格</p>
                    <div class="sell-info-input-wrapper">
                        <input class="sell-info-input" name="price" type="text" value="{{ old('price') }}">
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

@section('scripts')
    <script>
        function previewImage(event) {
            var input = event.target; // 画像選択されたinput
            var wrapper = document.querySelector('.sell-image-wrapper'); // 画像のラッパー
            var imgElement = document.querySelector('.sell-item-image'); // 画像表示の要素
            var label = document.querySelector('.sell-image-button'); // ボタン要素

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    imgElement.src = e.target.result; // 選択された画像を表示
                    imgElement.style.display = 'block'; // 画像を表示
                };
                reader.readAsDataURL(input.files[0]); // 画像をデータURLとして読み込む
            }

            // ラッパーにimage-selectedクラスを追加
            wrapper.classList.add('image-selected');
        }
    </script>
@endsection
