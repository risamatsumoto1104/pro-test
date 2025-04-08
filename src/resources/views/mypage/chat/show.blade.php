@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/chats/show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/rating-modal.css') }}">
@endsection

@section('content')
    @if ($role === 'buyer')
        {{-- 購入者の場合 --}}
        <div class="buyer-container">
            {{-- サイドバー --}}
            <aside class="sidebar">
                <a class="back-link" href="{{ route('mypage', ['tab' => 'trading']) }}">取引一覧へ戻る</a>
                <h2 class="sidebar-title">その他の購入取引</h2>
                @foreach ($boughtTradingItems as $item)
                    <nav class="sidebar-nav">
                        <ul class="trading-item-list">
                            <li class="trading-item">
                                <a class="item-link"
                                    href="{{ route('mypage.chat.show', ['item_id' => $item->item_id, 'user_id' => $item->seller_user_id]) }}">{{ $item->item_name }}</a>
                            </li>
                        </ul>
                    </nav>
                @endforeach
            </aside>

            <div class="right-column-container">
                {{-- コンテンツ --}}
                <article class="content">
                    <div class="chat-room-info">
                        {{-- 出品者との取引画面 --}}
                        <div class="chat-room-left-container">
                            <img class="chat-room-user-img"
                                src="{{ $otherProfile && $otherProfile->profile_image
                                    ? (file_exists(storage_path('storage/' . $otherProfile->profile_image))
                                        ? asset('storage/' . $otherProfile->profile_image)
                                        : asset('profile_images/' . $otherProfile->profile_image))
                                    : asset('profile_images/default-profile.png') }}"
                                alt="ユーザー画像">
                            <h2 class="chat-room-title">「{{ $otherProfile->user->name }}」さんとの取引画面</h2>
                        </div>
                        <div class="chat-room-right-container">
                            <button class="transaction-complete-button" id="completeTransactionButton">取引を完了する</button>
                        </div>
                    </div>

                    <div class="trading-item-info">
                        <img class="item-img"
                            src="{{ file_exists(public_path('item_images/' . $tradingItem->item_image))
                                ? asset('item_images/' . $tradingItem->item_image)
                                : asset('storage/' . $tradingItem->item_image) }}"
                            alt="商品画像">
                        <div class="item-details">
                            <h2 class="item-name">{{ $tradingItem->item_name }}</h2>
                            <p class="item-price">{{ number_format($tradingItem->price) }}</p>
                        </div>
                    </div>

                    <div class="message-container">
                        @foreach ($chatMessages as $message)
                            {{-- 相手メッセージ（出品者） --}}
                            @if ($message->sender_id === $otherProfile->user->user_id || $message->item_id === $itemId)
                                <div class="left-user-message-container">
                                    <div class="left-user-details">
                                        <img class="left-user-message-img"
                                            src="{{ $otherProfile && $otherProfile->profile_image
                                                ? (file_exists(storage_path('storage/' . $otherProfile->profile_image))
                                                    ? asset('storage/' . $otherProfile->profile_image)
                                                    : asset('profile_images/' . $otherProfile->profile_image))
                                                : asset('profile_images/default-profile.png') }}"
                                            alt="ユーザー名">
                                        <p class="left-user-name">{{ $otherProfile->user->name }}</p>
                                    </div>
                                    <p class="left-user-message">{{ $message->chat_message }}</p>
                                    @if ($message->message_image)
                                        <img class="left-user-img" src="{{ asset('storage/' . $message->message_image) }}"
                                            alt="メッセージ画像">
                                    @endif
                                </div>
                            @endif
                        @endforeach
                        @foreach ($chatMessages as $message)
                            {{-- 自分メッセージ（購入者） --}}
                            @if ($message->sender_id === $myProfile->user->user_id || $message->item_id === $itemId)
                                <div class="right-user-message-container">
                                    <div class="right-user-details">
                                        <p class="right-user-name">{{ $myProfile->user->name }}</p>
                                        <img class="right-user-message-img"
                                            src="{{ $myProfile && $myProfile->profile_image
                                                ? (file_exists(storage_path('storage/' . $myProfile->profile_image))
                                                    ? asset('storage/' . $myProfile->profile_image)
                                                    : asset('profile_images/' . $myProfile->profile_image))
                                                : asset('profile_images/default-profile.png') }}"
                                            alt="ユーザー名">
                                    </div>
                                    <p class="right-user-message">{{ $message->chat_message }}</p>
                                    @if ($message->message_image)
                                        <img class="right-user-img" src="{{ asset('storage/' . $message->message_image) }}"
                                            alt="メッセージ画像">
                                    @endif
                                    <div class="message-actions">
                                        {{-- 編集ボタン --}}
                                        <button class="edit-button" type="button"
                                            onclick="openEditForm({{ $message->chat_id }}, '{{ addslashes($message->chat_message) }}', '{{ $message->message_image ? asset('storage/' . $message->message_image) : '' }}')">編集</button>

                                        {{-- 削除ボタン --}}
                                        <form class="delete-form"
                                            action="{{ route('mypage.chat.destroy', ['item_id' => $tradingItem->item_id, 'user_id' => $tradingItem->seller->user_id, 'chat_id' => $message->chat_id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <input class="delete-button" type="submit" value="削除">
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </article>

                {{-- フッター --}}
                <footer class="footer">
                    {{-- メッセージの投稿 --}}
                    <form class="message-form"
                        action="{{ route('mypage.chat.store', ['item_id' => $tradingItem->item_id, 'user_id' => $tradingItem->seller->user_id, 'sender_id' => $tradingItem->purchase->buyer_user_id, 'role' => 'buyer']) }}"
                        method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="confirm-container">
                            {{-- 送信画像の確認 --}}
                            <img class="image-upload-preview" id="preview" src="" alt=""
                                style="display: none;">

                            <div class="error-container">
                                @error('chat_message')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                                @error('message_image')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="submit-container">
                            {{-- 本文 --}}
                            <textarea class="message-input" name="chat_message" rows="1" placeholder="取引メッセージを記入してください">{{ old('chat_message') }}</textarea>

                            {{-- 画像アップロードボタン --}}
                            <div class="image-upload-container">
                                <label class="image-upload-button" for="image-upload-input">画像を追加</label>
                                <input class="image-upload-input" name="message_image" id="image-upload-input"
                                    type="file">
                            </div>

                            {{-- hidden --}}
                            <input name="sender_id" type="hidden" value="{{ $myProfile->user->user_id }}">
                            <input name="item_id" type="hidden" value="{{ $tradingItem->item_id }}">
                            <input name="sender_role" type="hidden" value="buyer">

                            {{-- 送信ボタン --}}
                            <button class="submit-button" type="submit">
                                <img class="send-icon" src="{{ asset('icon_images/inputbutton.png') }}" alt="送信アイコン">
                            </button>
                        </div>
                    </form>

                    {{-- メッセージの編集 --}}
                    @foreach ($chatMessages as $message)
                        <form class="update-message-form" id="edit-form-{{ $message->chat_id }}"
                            action="{{ route('mypage.chat.update', ['item_id' => $tradingItem->item_id, 'user_id' => $tradingItem->seller->user_id, 'chat_id' => $message->chat_id]) }}"
                            method="post" enctype="multipart/form-data" style="display: none;">
                            @csrf
                            @method('PATCH')

                            <div class="update-confirm-container">
                                {{-- 送信画像の確認 --}}
                                <img class="update-image-upload-preview" id="preview-{{ $message->chat_id }}"
                                    src="" alt="" style="display: none;">

                                <div class="error-container">
                                    @error('chat_message')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                    @error('message_image')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="update-submit-container">
                                {{-- 本文 --}}
                                <textarea class="update-message-input" name="chat_message" id="edit-textarea" rows="1"
                                    placeholder="取引メッセージを記入してください">{{ old('chat_message', $message->chat_message) }}</textarea>

                                {{-- 画像アップロードボタン --}}
                                <div class="update-image-upload-container">
                                    <label class="update-image-upload-button"
                                        for="image-upload-input-{{ $message->chat_id }}">画像を追加</label>
                                    <input class="update-image-upload-input" name="message_image"
                                        id="image-upload-input-{{ $message->chat_id }}" type="file">
                                </div>

                                {{-- 送信ボタン --}}
                                <button class="update-submit-button" type="submit">
                                    <img class="update-send-icon" src="{{ asset('icon_images/inputbutton.png') }}"
                                        alt="送信アイコン">
                                </button>
                            </div>
                        </form>
                    @endforeach
                </footer>
            </div>
        </div>
    @elseif($role === 'seller')
        {{-- 出品者の場合 --}}
        <div class="seller-container">
            {{-- サイドバー --}}
            <aside class="sidebar">
                <a class="back-link" href="{{ route('mypage', ['tab' => 'trading']) }}">取引一覧へ戻る</a>
                <h2 class="sidebar-title">その他の出品取引</h2>
                @foreach ($soldTradingItems as $item)
                    <nav class="sidebar-nav">
                        <ul class="trading-item-list">
                            <li class="trading-item">
                                <a class="item-link"
                                    href="{{ route('mypage.chat.show', ['item_id' => $item->item_id, 'user_id' => $item->purchase->buyer_user_id]) }}">{{ $item->item_name }}</a>
                            </li>
                        </ul>
                    </nav>
                @endforeach
            </aside>

            <div class="right-column-container">
                {{-- コンテンツ --}}
                <article class="content">
                    <div class="chat-room-info">
                        {{-- 購入者との取引画面 --}}
                        <div class="chat-room-left-container">
                            <img class="chat-room-user-img"
                                src="{{ $otherProfile && $otherProfile->profile_image
                                    ? (file_exists(storage_path('storage/' . $otherProfile->profile_image))
                                        ? asset('storage/' . $otherProfile->profile_image)
                                        : asset('profile_images/' . $otherProfile->profile_image))
                                    : asset('profile_images/default-profile.png') }}"
                                alt="ユーザー画像">
                            <h2 class="chat-room-title">「{{ $otherProfile->user->name }}」さんとの取引画面</h2>
                        </div>
                    </div>

                    <div class="trading-item-info">
                        <img class="item-img"
                            src="{{ file_exists(public_path('item_images/' . $tradingItem->item_image))
                                ? asset('item_images/' . $tradingItem->item_image)
                                : asset('storage/' . $tradingItem->item_image) }}"
                            alt="商品画像">
                        <div class="item-details">
                            <h2 class="item-name">{{ $tradingItem->item_name }}</h2>
                            <p class="item-price">{{ number_format($tradingItem->price) }}</p>
                        </div>
                    </div>

                    <div class="message-container">
                        @foreach ($chatMessages as $message)
                            {{-- 相手メッセージ(購入者) --}}
                            @if ($message->sender_id === $otherProfile->user->user_id || $message->item_id === $itemId)
                                <div class="left-user-message-container">
                                    <div class="left-user-details">
                                        <img class="left-user-message-img"
                                            src="{{ $otherProfile && $otherProfile->profile_image
                                                ? (file_exists(storage_path('storage/' . $otherProfile->profile_image))
                                                    ? asset('storage/' . $otherProfile->profile_image)
                                                    : asset('profile_images/' . $otherProfile->profile_image))
                                                : asset('profile_images/default-profile.png') }}"
                                            alt="ユーザー名">
                                        <p class="left-user-name">{{ $otherProfile->user->name }}</p>
                                    </div>
                                    <p class="left-user-message">{{ $message->chat_message }}</p>
                                    @if ($message->message_image)
                                        <img class="left-user-img"
                                            src="{{ asset('storage/' . $message->message_image) }}" alt="メッセージ画像">
                                    @endif
                                </div>
                            @endif
                        @endforeach
                        @foreach ($chatMessages as $message)
                            {{-- 自分メッセージ（出品者） --}}
                            @if ($message->sender_id === $myProfile->user->user_id || $message->item_id === $itemId)
                                <div class="right-user-message-container">
                                    <div class="right-user-details">
                                        <p class="right-user-name">{{ $myProfile->user->name }}</p>
                                        <img class="right-user-message-img"
                                            src="{{ $myProfile && $myProfile->profile_image
                                                ? (file_exists(storage_path('storage/' . $myProfile->profile_image))
                                                    ? asset('storage/' . $myProfile->profile_image)
                                                    : asset('profile_images/' . $myProfile->profile_image))
                                                : asset('profile_images/default-profile.png') }}"
                                            alt="ユーザー名">
                                    </div>
                                    <p class="right-user-message">{{ $message->chat_message }}</p>
                                    @if ($message->message_image)
                                        <img class="right-user-img"
                                            src="{{ asset('storage/' . $message->message_image) }}" alt="メッセージ画像">
                                    @endif
                                    <div class="message-actions">
                                        {{-- 編集ボタン --}}
                                        <button class="edit-button" type="button"
                                            onclick="openEditForm({{ $message->chat_id }}, '{{ addslashes($message->chat_message) }}', '{{ $message->message_image ? asset('storage/' . $message->message_image) : '' }}')">編集</button>

                                        {{-- 削除ボタン --}}
                                        <form class="delete-form"
                                            action="{{ route('mypage.chat.destroy', ['item_id' => $tradingItem->item_id, 'user_id' => $tradingItem->purchase->buyer_user_id, 'chat_id' => $message->chat_id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <input class="delete-button" type="submit" value="削除">
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </article>

                {{-- フッター --}}
                <footer class="footer">
                    {{-- メッセージの投稿 --}}
                    <form class="message-form"
                        action="{{ route('mypage.chat.store', ['item_id' => $tradingItem->item_id, 'user_id' => $tradingItem->purchase->buyer_user_id, 'sender_id' => $tradingItem->seller->user_id, 'role' => 'seller']) }}"
                        method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="confirm-container">
                            {{-- 送信画像の確認 --}}
                            <img class="image-upload-preview" id="preview" src="" alt=""
                                style="display: none;">

                            <div class="error-container">
                                @error('chat_message')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                                @error('message_image')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="submit-container">
                            {{-- 本文 --}}
                            <textarea class="message-input" name="chat_message" rows="1" placeholder="取引メッセージを記入してください">{{ old('chat_message') }}</textarea>

                            {{-- 画像アップロードボタン --}}
                            <div class="image-upload-container">
                                <label class="image-upload-button" for="image-upload-input">画像を追加</label>
                                <input class="image-upload-input" name="message_image" id="image-upload-input"
                                    type="file">
                            </div>

                            {{-- hidden --}}
                            <input name="sender_id" type="hidden" value="{{ $myProfile->user->user_id }}">
                            <input name="item_id" type="hidden" value="{{ $tradingItem->item_id }}">
                            <input name="sender_role" type="hidden" value="seller">

                            {{-- 送信ボタン --}}
                            <button class="submit-button" type="submit">
                                <img class="send-icon" src="{{ asset('icon_images/inputbutton.png') }}" alt="送信アイコン">
                            </button>
                        </div>
                    </form>

                    {{-- メッセージの編集 --}}
                    @foreach ($chatMessages as $message)
                        <form class="update-message-form" id="edit-form-{{ $message->chat_id }}"
                            action="{{ route('mypage.chat.update', ['item_id' => $tradingItem->item_id, 'user_id' => $tradingItem->purchase->buyer_user_id, 'chat_id' => $message->chat_id]) }}"
                            method="post" enctype="multipart/form-data" style="display: none;">
                            @csrf
                            @method('PATCH')

                            <div class="update-confirm-container">
                                {{-- 送信画像の確認 --}}
                                <img class="update-image-upload-preview" id="preview-{{ $message->chat_id }}"
                                    src="" alt="" style="display: none;">

                                <div class="error-container">
                                    @error('chat_message')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                    @error('message_image')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="update-submit-container">
                                {{-- 本文 --}}
                                <textarea class="update-message-input" name="chat_message" id="edit-textarea" rows="1"
                                    placeholder="取引メッセージを記入してください">{{ old('chat_message', $message->chat_message) }}</textarea>

                                {{-- 画像アップロードボタン --}}
                                <div class="update-image-upload-container">
                                    <label class="update-image-upload-button"
                                        for="image-upload-input-{{ $message->chat_id }}">画像を追加</label>
                                    <input class="update-image-upload-input" name="message_image"
                                        id="image-upload-input-{{ $message->chat_id }}" type="file">
                                </div>

                                {{-- 送信ボタン --}}
                                <button class="update-submit-button" type="submit">
                                    <img class="update-send-icon" src="{{ asset('icon_images/inputbutton.png') }}"
                                        alt="送信アイコン">
                                </button>
                            </div>
                        </form>
                    @endforeach
                </footer>
            </div>
        </div>
    @endif
@endsection

@section('modals')
    @include('components.rating-modal')
@endsection

@section('scripts')
    <script>
        // メッセージの投稿用フォームを取得
        const postTextarea = document.querySelector('.message-input');
        const postForm = document.querySelector('.message-form');
        const postImageInput = postForm.querySelector('.image-upload-input'); // 画像のinput要素

        // ページ固有キーを作成
        const pageKey = 'chat_message_draft_' + window.location.pathname;

        // ページ読み込み時に復元
        window.addEventListener('DOMContentLoaded', () => {
            const saved = sessionStorage.getItem(pageKey);
            if (saved) {
                postTextarea.value = saved;
            }
        });

        // 入力のたびに保存
        postTextarea.addEventListener('input', () => {
            sessionStorage.setItem(pageKey, postTextarea.value);
        });

        // 送信時に削除（送信されたら下書きも消す）
        postForm.addEventListener('submit', () => {
            sessionStorage.removeItem(pageKey);
        });

        if (postImageInput) {
            postImageInput.addEventListener('change', (event) => {
                const input = event.target; // 画像選択されたinput
                const imgElement = document.querySelector('.image-upload-preview'); // 画像表示の要素
                const reader = new FileReader();

                if (input.files && input.files[0]) {
                    reader.onload = function(e) {
                        imgElement.src = e.target.result; // 選択された画像を表示
                        imgElement.style.display = 'block'; // 画像を表示
                    };
                    reader.readAsDataURL(input.files[0]); // 画像をデータURLとして読み込む
                }
            });
        }

        // 編集フォームを表示
        function openEditForm(chatId, messageContent, imageUrl) {
            // 編集フォームを表示
            const form = document.querySelector(`#edit-form-${chatId}`);
            const textarea = form.querySelector('#edit-textarea');
            const imagePreview = form.querySelector(`#preview-${chatId}`);
            const imageInput = form.querySelector(`#image-upload-input-${chatId}`); // 画像のinput要素

            // 編集フォームを表示
            form.style.display = 'block';

            // 既存のメッセージ内容をtextareaに設定
            textarea.value = messageContent;

            // 画像URLが存在すればプレビューに表示
            if (imageUrl) {
                imagePreview.src = imageUrl; // 画像URLをプレビューに設定
                imagePreview.style.display = 'block'; // 画像を表示
            } else {
                imagePreview.style.display = 'none'; // 画像がない場合は非表示
            }

            // 他のフォームを隠す（既に表示されているフォームを隠す場合）
            const allForms = document.querySelectorAll('.message-form, .update-message-form');
            allForms.forEach(f => {
                if (f !== form) {
                    f.style.display = 'none';
                }
            });

            if (imageInput) {
                imageInput.removeEventListener('change', handleImageChange); // イベントリスナーが重複しないように削除
                imageInput.addEventListener('change', handleImageChange); // 画像選択時のイベントを設定
            }
        }

        // 画像変更時の処理を分離して関数化
        function handleImageChange(event) {
            const input = event.target;
            const form = input.closest('.update-message-form');
            const imagePreview = form.querySelector('.update-image-upload-preview');

            // 既存の画像プレビューを空にする
            imagePreview.style.display = 'none';
            imagePreview.src = '';

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const role = "{{ $role }}";
            const buyerModal = document.getElementById('buyerRatingModal');
            const sellerModal = document.getElementById('sellerRatingModal');

            let ratingInput;
            if (role === 'buyer') {
                ratingInput = document.getElementById('buyerRatingInput');
            } else if (role === 'seller') {
                ratingInput = document.getElementById('sellerRatingInput');
            }
            const starContainers = document.querySelectorAll('#starContainer');

            // 星クリック処理（両方対応）
            starContainers.forEach(container => {
                const stars = container.querySelectorAll('.rating-modal-star');
                stars.forEach(star => {
                    star.addEventListener('click', () => {
                        const rating = star.dataset.value;

                        // セット
                        if (ratingInput) {
                            ratingInput.value = rating;
                        }

                        // 星の見た目更新
                        stars.forEach(s => {
                            s.classList.remove('active');
                            if (s.dataset.value <= rating) {
                                s.classList.add('active');
                            }
                        });
                    });
                });
            });

            // 購入者（ボタンでモーダル開閉）
            const completeButton = document.getElementById('completeTransactionButton');
            if (role === 'buyer' && buyerModal && completeButton) {
                let isOpen = false;
                completeButton.addEventListener('click', () => {
                    buyerModal.style.display = isOpen ? 'none' : 'block';
                    isOpen = !isOpen;
                });
            }

            // 出品者（条件が true なら自動表示）
            @if ($role === 'seller' && $shouldShowModal)
                if (sellerModal) {
                    sellerModal.style.display = 'block'; // モーダルを表示
                }
            @endif
        });
    </script>
@endsection
