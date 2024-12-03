<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddressController extends Controller
{
    // 住所更新処理
    public function update(Request $request, $item_id)
    {
        return view('purchase.address_update', compact('item_id'));
    }
}
