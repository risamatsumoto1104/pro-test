@if ($role === 'buyer')
    {{-- 購入者の場合(出品者への評価) --}}
    <div class="rating-modal-container" id="buyerRatingModal" style="display: none;">
        <p class="rating-modal-completion-message">購入取引が完了しました。</p>
        <div class="rating-modal-details">
            <p class="rating-modal-question-message">今回の取引相手はどうでしたか？</p>
            <div class="rating-modal-stars" id="starContainer">
                @for ($i = 1; $i <= 5; $i++)
                    <p class="rating-modal-star" data-value="{{ $i }}">&#9733;</p>
                @endfor
            </div>
        </div>
        <form class="rating-modal-form"
            action="{{ route('ratings.store', ['item_id' => $tradingItem->item_id, 'user_id' => $otherProfile->user->user_id, 'evaluator_id' => $myProfile->user->user_id, 'role' => 'buyer']) }}"
            method="post">
            @csrf
            <input type="hidden" name="rating" id="buyerRatingInput" value="">
            <input type="hidden" name="evaluator_role" value="buyer">
            <input class="rating-modal-submit" type="submit" value="送信する">
        </form>
    </div>
@elseif($role === 'seller')
    {{-- 出品者の場合(購入者への評価) --}}
    <div class="rating-modal-container" id="sellerRatingModal" style="display: none;">
        <p class="rating-modal-completion-message">出品取引が完了しました。</p>
        <div class="rating-modal-details">
            <p class="rating-modal-question-message">今回の取引相手はどうでしたか？</p>
            <div class="rating-modal-stars" id="starContainer">
                @for ($i = 1; $i <= 5; $i++)
                    <p class="rating-modal-star" data-value="{{ $i }}">&#9733;</p>
                @endfor
            </div>
        </div>
        <form class="rating-modal-form"
            action="{{ route('ratings.store', ['item_id' => $tradingItem->item_id, 'user_id' => $otherProfile->user->user_id, 'evaluator_id' => $myProfile->user->user_id, 'role' => 'seller']) }}"
            method="post">
            @csrf
            <input type="hidden" name="rating" id="sellerRatingInput" value="">
            <input type="hidden" name="evaluator_role" value="seller">
            <input class="rating-modal-submit" type="submit" value="送信する">
        </form>
    </div>
@endif
