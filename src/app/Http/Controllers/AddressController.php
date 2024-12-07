<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function edit($item_id)
    {
        return view('purchase.edit', compact('item_id'));
    }

    // 住所更新処理
    public function update(AddressRequest $request, $item_id)
    {
        return redirect('/purchase/address/' . $item_id);
    }
}
