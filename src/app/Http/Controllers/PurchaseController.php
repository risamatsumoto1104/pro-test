<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function confirm($item_id)
    {
        return view('purchase.confirm', compact('item_id'));
    }

    // 購入処理
    public function store($item_id)
    {
        return view('/mypage?tab=buy');
    }
}
