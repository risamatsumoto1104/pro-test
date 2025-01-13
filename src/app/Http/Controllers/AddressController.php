<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\Address;
use App\Models\AddressItem;
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

        // Item モデルを取得
        $item = Item::findOrFail($item_id);

        // 住所を更新または新規作成
        $address = Address::where('user_id', Auth::id())->first();

        if (!$address) {
            // 住所が存在しない場合は新規作成
            $address = Address::create([
                'postal_code' => $validated['postal_code'],
                'address' => $validated['address'],
                'building' => $validated['building'],
                'user_id' => Auth::id(),
            ]);
        } else {
            // 住所が存在する場合は更新
            $address->update([
                'postal_code' => $validated['postal_code'],
                'address' => $validated['address'],
                'building' => $validated['building'],
            ]);
        }

        // 中間テーブル（address_item）に保存
        AddressItem::updateOrCreate(
            ['item_id' => $item->item_id], // item_idが存在する場合は更新、存在しない場合は新規作成
            ['address_id' => $address->address_id]
        );

        // 購入確認ページにリダイレクト
        return redirect()->route('item.purchase', ['item_id' => $item_id]);
    }
}
