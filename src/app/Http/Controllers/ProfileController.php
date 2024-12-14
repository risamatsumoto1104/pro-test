<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // プロフィール画面の表示
    public function show(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;

        // 出品した商品一覧
        $soldItems = Item::where('seller_user_id', $user->id)->get();

        // 購入した商品一覧
        $boughtItems = Purchase::where('buyer_user_id', $user->id)
            ->join('items', 'purchases.item_id', '=', 'items.item_id')
            ->get(['items.*']);

        // タブの状態
        $tab = $request->get('tab', 'sell');

        return view('mypage.profile.show', compact('user', 'profile', 'soldItems', 'boughtItems', 'tab'));
    }

    // プロフィール設定画面を表示
    public function edit()
    {
        $user = Auth::user();
        return view('mypage.profile.edit', compact('user'));
    }
}
