<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Address;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Rating;

class ProfileController extends Controller
{
    // プロフィール画面の表示
    public function show(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;

        // 出品した商品一覧
        $soldItems = Item::where('seller_user_id', $user->user_id)->get();
        // soldItems から item_id の配列を抽出
        $soldItemIds = $soldItems->pluck('item_id');
        // 出品者の評価平均を計算
        $soldItemUserRating = Rating::whereIn('item_id', $soldItemIds)
            ->where('evaluator_id', '!=', $user->user_id) //自分ではないユーザー
            ->avg('rating');
        // 評価があれば四捨五入、なければnullに設定
        $soldItemUserAverageRating = $soldItemUserRating ? round($soldItemUserRating) : 0;


        // 購入した商品一覧
        $boughtItems = Purchase::where('buyer_user_id', $user->user_id)
            ->join('items', 'purchases.item_id', '=', 'items.item_id')
            ->get(['items.*']);
        // boughtItems から item_id の配列を抽出
        $boughtItemIds = $boughtItems->pluck('item_id');
        // 購入者の評価平均を計算
        $boughtItemUserRating = Rating::whereIn('item_id', $boughtItemIds)
            ->where('evaluator_id', '!=', $user->user_id) //自分ではないユーザー
            ->avg('rating');
        // 評価があれば四捨五入、なければnullに設定
        $boughtItemUserAverageRating = $boughtItemUserRating ? round($boughtItemUserRating) : 0;

        // 購入した商品について「売り切れ(sold)」状態を判定
        foreach ($boughtItems as $item) {
            $isSold = Purchase::where('item_id', $item->item_id)->exists();
            if ($isSold) {
                $item->status = 'sold'; // 売り切れ状態に設定
            }
        }

        // 取引中（自分が評価していない）の商品
        if ($soldItemIds->isNotEmpty()) {
            // 出品商品についての表示
            $soldTradingItems = Item::whereIn('item_id', $soldItemIds)
                // 出品商品が購入されているか確認
                ->whereHas('purchase', function ($query) {
                    $query->whereNotNull('buyer_user_id'); //buyer_user_idがnullでない
                })
                // 出品商品が評価されていないか確認
                ->whereDoesntHave('rating', function ($query) {
                    $query->where('evaluator_id', auth()->id())
                        ->orWhereNull('evaluator_id'); //自分が評価していない
                })
                ->with(['purchase', 'chatMessages'])
                ->get()
                ->map(function ($item) {
                    $item->item_role = 'sold';
                    return $item;
                });

            // 出品商品についてのチャット未読数
            $soldChatUnreadCounts = $soldTradingItems->mapWithKeys(function ($soldTradingItem) {
                $count = ChatMessage::where('item_id', $soldTradingItem->item_id)
                    ->where('sender_role', 'buyer') //相手
                    ->where('is_read', 0) // 未読のもののみ
                    ->count();

                return [$soldTradingItem->item_id => $count];
            });
        } else {
            $soldTradingItems = collect();
            $soldChatUnreadCounts = collect();
        }

        if ($boughtItemIds->isNotEmpty()) {
            // 購入商品についての表示
            $boughtTradingItems = Item::whereIn('item_id', $boughtItemIds)
                // 購入者（自分）が評価していないか確認
                ->whereDoesntHave('rating', function ($query) {
                    $query->where('evaluator_id', auth()->id())
                        ->orWhereNull('evaluator_id'); //自分が評価していない
                })
                ->with(['chatMessages'])
                ->get()
                ->map(function ($item) {
                    $item->item_role = 'bought';
                    return $item;
                });

            // 購入商品についてのチャット未読数
            $boughtChatUnreadCounts = $boughtTradingItems->mapWithKeys(function ($boughtTradingItem) {
                $count = ChatMessage::where('item_id', $boughtTradingItem->item_id)
                    ->where('sender_role', 'seller') //相手
                    ->where('is_read', 0) // 未読のもののみ
                    ->count();

                return [$boughtTradingItem->item_id => $count];
            });
        } else {
            $boughtTradingItems = collect();
            $boughtChatUnreadCounts = collect();
        }

        // 全ての商品についての並べ替え
        $allTradingItems = $soldTradingItems
            ->merge($boughtTradingItems)
            // 未読メッセージの有無と、最新メッセージ作成日時で並べ替え
            ->sortByDesc(function ($item) use ($user) {
                // 相手発信の未読メッセージがあれば優先
                $hasUnread = $item->chatMessages->filter(function ($message) use ($user) {
                    return $message->sender_id != $user->user_id && $message->is_read == 0;
                })->isNotEmpty();

                // 未読メッセージがあれば、先に並べる
                $unreadPriority = $hasUnread ? 1 : 0;

                // 最新メッセージ日時を取得
                $latestMessage = $item->chatMessages->sortByDesc('created_at')->first();
                $latestMessageTime = $latestMessage ? $latestMessage->created_at : null;

                // 未読メッセージの有無で優先順位を付け、最新メッセージ日時で並べ替え
                return [$unreadPriority, $latestMessageTime];
            })
            ->values(); // キーを振り直す（オプション）

        // 評価の合算
        $totalUserAverageRating = 0;  // 初期値
        if ($soldItemUserAverageRating && $boughtItemUserAverageRating) {
            $totalUserAverageRating = ($soldItemUserAverageRating) + ($boughtItemUserAverageRating);
            // 評価があれば四捨五入、なければnullに設定
            $userAverageRating = $totalUserAverageRating ? round($totalUserAverageRating / 2) : null;
        } else {
            // どちらかが0であればそのままの値を使用
            $userAverageRating = $soldItemUserAverageRating ?: $boughtItemUserAverageRating;
        }

        // タブの状態
        $tab = $request->get('tab', 'sell');

        return view('mypage.profile.show', compact('user', 'profile', 'soldItems', 'boughtItems', 'allTradingItems', 'soldChatUnreadCounts', 'boughtChatUnreadCounts', 'userAverageRating', 'tab'));
    }

    // プロフィール設定画面を表示
    public function edit(Request $request)
    {
        // ログイン中のユーザーを取得
        $user = Auth::user();
        // ユーザーに関連するプロフィール情報を取得
        $profile = $user->profile;

        return view('mypage.profile.edit', compact('user', 'profile'));
    }

    public function update(ProfileRequest $profileRequest, AddressRequest $addressRequest)
    {
        $user = auth()->user();

        // バリデーション済みデータ取得
        $profileValidated = $profileRequest->validated();
        $addressValidated = $addressRequest->validated();

        // 画像処理
        if ($profileRequest->hasFile('profile_image')) {
            $imagePath = $profileRequest->file('profile_image')->store('public');

            // 古い画像を削除
            if ($user->profile && $user->profile->profile_image) {
                Storage::disk('public')->delete($user->profile->profile_image);
            }

            $profileValidated['profile_image'] = basename($imagePath);
        }

        // ユーザー名更新（usersテーブルのnameカラムを更新）
        if (isset($addressValidated['name'])) {
            $user->update(['name' => $addressValidated['name']]); // ユーザー名を更新
            unset($addressValidated['name']);
        }

        // プロフィール更新または作成
        $addressValidated['profile_image'] = $profileValidated['profile_image'] ?? null; // 画像も保存
        if ($user->profile) {
            $user->profile()->update($addressValidated);
        } else {
            $addressValidated['user_id'] = $user->user_id;
            $user->profile()->create($addressValidated);
        }

        // **Addressテーブルに保存または更新**
        Address::updateOrCreate(
            ['user_id' => $user->user_id], // `user_id` で検索
            [
                'postal_code' => $addressValidated['postal_code'] ?? null,
                'address' => $addressValidated['address'] ?? null,
                'building' => $addressValidated['building'] ?? null,
            ]
        );

        // 更新完了とともにリダイレクト
        return redirect()->route('home');
    }
}
