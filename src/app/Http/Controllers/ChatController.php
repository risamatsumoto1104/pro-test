<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatRequest;
use App\Models\ChatMessage;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    // チャット取引画面の表示
    public function show(Request $request)
    {
        $user = auth()->user();

        // ルートから取得
        $itemId = $request->route('item_id');
        $userId = $request->route('user_id');

        $tradingItem = Item::with('seller', 'purchase')->findOrFail($itemId);

        $otherProfile = Profile::with('user')->findOrFail($userId);
        $myProfile = Profile::with('user')->findOrFail($user->user_id);

        $chatMessages = ChatMessage::with('sender')
            ->where('item_id', $itemId)
            ->whereIn('sender_id', [$userId, $user->user_id])
            ->orderBy('created_at', 'asc')
            ->get();

        // 出品した商品一覧
        $soldItems = Item::where('seller_user_id', $user->user_id)->get();
        // soldItems から item_id の配列を抽出
        $soldItemIds = $soldItems->pluck('item_id');

        // 購入した商品一覧
        $boughtItems = Purchase::where('buyer_user_id', $user->user_id)
            ->join('items', 'purchases.item_id', '=', 'items.item_id')
            ->get(['items.*']);
        // boughtItems から item_id の配列を抽出
        $boughtItemIds = $boughtItems->pluck('item_id');

        // 取引中の商品
        if ($soldItemIds->isNotEmpty()) {
            $soldTradingItems = Item::whereIn('item_id', $soldItemIds)
                ->whereHas('purchase', function ($query) {
                    $query->whereNotNull('buyer_user_id');
                })
                ->whereDoesntHave('rating', function ($query) {
                    $query->where('evaluator_id', auth()->id())
                        ->orWhereNull('evaluator_id');
                })
                ->with('purchase')
                ->get();
        } else {
            $soldTradingItems = collect();
        }

        if ($boughtItemIds->isNotEmpty()) {
            $boughtTradingItems = Item::whereIn('item_id', $boughtItemIds)
                ->whereDoesntHave('rating', function ($query) {
                    $query->where('evaluator_id', auth()->id())
                        ->orWhereNull('evaluator_id');
                })
                ->get();
        } else {
            $boughtTradingItems = collect();
        }

        // 出品者か購入者かを確認
        if ($tradingItem->seller_user_id === $user->user_id) {
            // 自分が出品者
            $role = 'seller';
        } elseif ($tradingItem->purchase && $tradingItem->purchase->buyer_user_id === $user->user_id) {
            // 自分が購入者
            $role = 'buyer';
        } else {
            abort(403, 'アクセス権限がありません');
        }

        return view('mypage.chat.show', compact('itemId', 'tradingItem', 'otherProfile', 'myProfile', 'chatMessages', 'soldTradingItems', 'boughtTradingItems', 'role'));
    }

    // メッセージの投稿
    public function store(ChatRequest $request)
    {
        $user = auth()->user();

        // ルートから取得
        $itemId = $request->route('item_id');
        $userId = $request->route('user_id'); //取引相手のID
        $senderId = $request->route('sender_id'); //自分のID
        $role = $request->route('role');

        if ($senderId == $user->user_id) {
            if ($role === 'buyer') {
                $chatContent = new ChatMessage();
                $chatContent->sender_id = $senderId;
                $chatContent->item_id = $itemId;
                $chatContent->sender_role = 'buyer';
                $chatContent->chat_message = $request->input('chat_message');

                if ($request->hasFile('message_image')) {
                    // ファイルを storage/app/public に保存
                    $path = $request->file('message_image')->store('public');

                    // パスを取得して保存（basenameでファイル名のみ取得）
                    $chatContent->message_image = basename($path);
                }

                $chatContent->save();

                return redirect()->route('mypage.chat.show', ['item_id' => $itemId, 'user_id' => $userId]);
            } else if ($role === 'seller') {
                $chatContent = new ChatMessage();
                $chatContent->sender_id = $senderId;
                $chatContent->item_id = $itemId;
                $chatContent->sender_role = 'seller';
                $chatContent->chat_message = $request->input('chat_message');

                if ($request->hasFile('message_image')) {
                    // ファイルを storage/app/public に保存
                    $path = $request->file('message_image')->store('public');

                    // パスを取得して保存（basenameでファイル名のみ取得）
                    $chatContent->message_image = basename($path);
                }

                $chatContent->save();

                return redirect()->route('mypage.chat.show', ['item_id' => $itemId, 'user_id' => $userId]);
            } else {
                return back()->with('error', 'メッセージが見つかりません。');
            }
        }
    }

    // メッセージの編集
    public function update(Request $request)
    {
        // ルートから取得
        $itemId = $request->route('item_id');
        $userId = $request->route('user_id'); //取引相手のID
        $chatId = $request->route('chat_id');

        $chatContent = ChatMessage::where('chat_id', $chatId)->first();

        if ($chatContent) {
            $chatContent->chat_message = $request->input('chat_message');

            if ($request->hasFile('message_image')) {
                // 古い画像ファイルを削除
                if ($chatContent->message_image) {
                    Storage::disk('public')->delete($chatContent->message_image);
                }

                // 新しい画像を保存
                $path = $request->file('message_image')->store('public');
                $chatContent->message_image = basename($path);
            }

            // 変更を保存
            $chatContent->save();

            return redirect()->route('mypage.chat.show', ['item_id' => $itemId, 'user_id' => $userId]);
        } else {
            return back()->with('error', 'メッセージが見つかりません。');
        }
    }

    // メッセージの削除
    public function destroy(Request $request)
    {
        $user = auth()->user();

        // ルートから取得
        $itemId = $request->route('item_id');
        $userId = $request->route('user_id'); //取引相手のID
        $chatId = $request->route('chat_id');

        $chatContent = ChatMessage::where('chat_id', $chatId)->first();

        if ($chatContent && ($chatContent->sender_id == $user->user_id)) {
            // 古い画像ファイルを削除
            if ($chatContent->message_image) {
                Storage::disk('public')->delete($chatContent->message_image);
            }
            $chatContent->delete();

            return redirect()->route('mypage.chat.show', ['item_id' => $itemId, 'user_id' => $userId]);
        } else {
            return back()->with('error', 'メッセージが見つかりません。');
        }
    }
}
