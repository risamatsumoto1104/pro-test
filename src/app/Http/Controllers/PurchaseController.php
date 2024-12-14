<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Address;

class PurchaseController extends Controller
{
    // 購入前の確認
    public function confirm($item_id)
    {
        // 商品情報を取得
        $item = Item::findOrFail($item_id);

        // ユーザーの住所情報を取得（ログインユーザーを想定）
        $user = auth()->user();
        $address = Address::where('user_id', $user->user_id)->first();

        // 必要な情報をビューに渡す
        return view('purchase.confirm', [
            'item' => $item,
            'address' => $address ?? (object) [
                'id' => '',
                'postal_code' => '',
                'address' => '',
                'building' => ''
            ]
        ]);
    }

    // 購入処理
    public function store(PurchaseRequest $request, $item_id)
    {
        return redirect('/mypage?tab=buy');
    }
}
