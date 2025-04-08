<?php

namespace App\Http\Controllers;

use App\Mail\SellerRatedNotification;
use App\Models\Item;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RatingController extends Controller
{
    // 評価機能
    public function store(Request $request)
    {
        $user = auth()->user();

        // ルートから取得
        $itemId = $request->route('item_id');
        $userId = $request->route('user_id'); //取引相手のID
        $evaluatorId = $request->route('evaluator_id'); //自分のID
        $role = $request->route('role');

        if ($evaluatorId == $user->user_id) {
            if ($role === 'buyer') {
                $chatContent = new Rating();
                $chatContent->evaluator_id = $evaluatorId;
                $chatContent->item_id = $itemId;
                $chatContent->evaluator_role = $request->input('evaluator_role');
                $chatContent->rating = $request->input('rating');

                $chatContent->save();

                $item = Item::findOrFail($itemId);
                if ($item->seller_user_id == $userId) {
                    $otherUser = User::findOrFail($userId);
                }
                $rating = $chatContent;

                // 出品者のメールに送信
                Mail::to($otherUser->email)->send(new SellerRatedNotification($item, $otherUser, $rating));

                return redirect()->route('home');
            } else if ($role === 'seller') {
                $chatContent = new Rating();
                $chatContent->evaluator_id = $evaluatorId;
                $chatContent->item_id = $itemId;
                $chatContent->evaluator_role = $request->input('evaluator_role');
                $chatContent->rating = $request->input('rating');

                $chatContent->save();

                return redirect()->route('home');
            } else {
                return back()->with('error', '評価が見つかりません。');
            }
        }
    }

    // メール送信機能
}
