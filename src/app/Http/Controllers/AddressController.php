<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Address;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function edit($item_id)
    {
        // ログイン中のユーザーを取得
        $user = Auth::user();

        // Item モデルを取得
        $item = Item::findOrFail($item_id);

        // ユーザーに関連する住所データを Profile テーブルから取得
        $profile = Profile::where('user_id', $user->user_id)->first(); // 住所情報を取得

        // ビューに item と profile を渡す
        return view('purchase.edit', compact('item', 'profile'));
    }

    public function update(AddressRequest $request, $item_id)
    {
        // バリデーション後のデータを取得
        $validated = $request->validated();

        // updateOrCreateを使って、住所が存在すれば更新、なければ新規作成
        Address::updateOrCreate(
            // 条件（item_id）で既存の住所を検索
            ['item_id' => $item_id],
            [
                'postal_code' => $validated['postal_code'],
                'address' => $validated['address'],
                'building' => $validated['building'],
                'user_id' => Auth::id(),
            ]
        );

        // リダイレクト
        return redirect()->route('item.purchase', ['item_id' => $item_id]);
    }
}
