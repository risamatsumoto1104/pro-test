<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Address;

class AddressController extends Controller
{
    public function edit($item_id)
    {
        // Item モデルを取得
        $item = Item::findOrFail($item_id);

        // Item に関連する Address モデルを取得（中間テーブル経由で取得）
        $address = $item->addresses()->first(); // 最初の住所を取得。複数ある場合は適宜変更

        if ($address) {
            // Address に関連する Purchase モデルを取得（Address モデルに関連する場合）
            $purchase = $address->purchases()->first(); // 最初の購入履歴を取得
        } else {
            $purchase = null;
        }

        // ビューに item と address を渡す
        return view('purchase.confirm', compact('item', 'address'));
    }

    // 住所更新処理
    public function update(AddressRequest $request, $item_id)
    {
        // バリデーション後の処理
        $validated = $request->validated();

        // 住所の更新処理など
        $address = Address::findOrFail($item_id);
        $address->update($validated);

        return redirect()->route('purchase.confirm', ['item_id' => $item_id]);
    }
}
