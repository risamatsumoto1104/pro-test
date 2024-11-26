<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function update(Request $request, $item_id)
    {
        // 住所更新処理
        return view("/purchase/{$item_id}");
    }
}
