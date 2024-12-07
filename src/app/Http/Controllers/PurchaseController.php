<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    // 購入前の確認
    public function confirm($item_id)
    {
        return view('purchase.confirm', compact('item_id'));
    }

    // 購入処理
    public function store(PurchaseRequest $request, $item_id)
    {
        return redirect('/mypage?tab=buy');
    }
}
