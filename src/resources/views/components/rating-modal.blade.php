@if ($role === 'buyer')
    {{-- 購入者の場合(出品者への評価) --}}
    <div class="rating-modal-container">
        <p class="rating-modal-completion-message">購入取引が完了しました。</p>
        <div class="rating-modal-details">
            <p class="rating-modal-question-message">今回の取引相手はどうでしたか？</p>
            <div class="rating-modal-stars">
                <p class="rating-modal-star">&#9733;</p>
                <p class="rating-modal-star">&#9733;</p>
                <p class="rating-modal-star">&#9733;</p>
                <p class="rating-modal-star">&#9733;</p>
                <p class="rating-modal-star">&#9733;</p>
            </div>
        </div>
        <form class="rating-modal-form" action="" method="post">
            <input class="rating-modal-submit" type="submit" value="送信する">
        </form>
    </div>
@elseif($role === 'seller')
    {{-- 出品者の場合(購入者への評価) --}}
    <div class="rating-modal-container">
        <p class="rating-modal-completion-message">出品取引が完了しました。</p>
        <div class="rating-modal-details">
            <p class="rating-modal-question-message">今回の取引相手はどうでしたか？</p>
            <div class="rating-modal-stars">
                <p class="rating-modal-star">&#9733;</p>
                <p class="rating-modal-star">&#9733;</p>
                <p class="rating-modal-star">&#9733;</p>
                <p class="rating-modal-star">&#9733;</p>
                <p class="rating-modal-star">&#9733;</p>
            </div>
        </div>
        <form class="rating-modal-form" action="" method="post">
            <input class="rating-modal-submit" type="submit" value="送信する">
        </form>
    </div>
@endif
