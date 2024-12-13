<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Item;

class PurchaseController extends Controller
{
    // 購入前の確認
    public function confirm($item_id)
    {
        // item_idを使って商品データを取得
        $item = Item::findOrFail($item_id);

        return view('purchase.confirm', compact('item_id'));
    }

    // 購入処理
    public function store(PurchaseRequest $request, $item_id)
    {
        return redirect('/mypage?tab=buy');
    }
}
