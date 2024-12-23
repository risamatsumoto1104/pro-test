<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Address;
use App\Models\Purchase;

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

        // ログインしているユーザー情報を取得
        $user = auth()->user();

        // ユーザーの住所情報を取得（必要に応じて他の住所情報を取得）
        $address = Address::where('user_id', $user->user_id)->first();

        // 同じ商品が既に購入されているか確認
        $existingPurchase = Purchase::where('item_id', $item_id)->first();

        // 既に購入されている場合、エラーメッセージを表示
        if ($existingPurchase) {
            return redirect()->back()->withErrors(['error' => 'この商品は既に購入されています。']);
        }

        // 購入データをpurchasesテーブルに保存
        $purchase = new Purchase();
        $purchase->buyer_user_id = $user->user_id;
        $purchase->item_id = $item_id;
        $purchase->address_id = $address->address_id;
        $purchase->payment_method = $request->input('payment_method');
        $purchase->save();

        // 購入後、マイページの「購入タブ」にリダイレクト
        return redirect()->route('mypage', ['tab' => 'buy']);
    }
}
