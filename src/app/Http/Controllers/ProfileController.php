<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // プロフィール画面の表示
    public function show(Request $request)
    {
        // クエリパラメータ 'tab' を取得（デフォルトで 'buy' を設定）
        $tab = $request->query('tab', 'buy');  // 'tab' がない場合は 'buy' をデフォルトに

        // 取得した 'tab' に基づいて処理を分ける
        if ($tab == 'buy') {
            return $this->showBoughtItems(); // 購入したアイテムを表示
        } elseif ($tab == 'sell') {
            return $this->showSoldItems(); // 出品したアイテムを表示
        }

        // デフォルトの表示処理（エラーハンドリング等もここで行うことが可能）
        return redirect('/mypage');
    }

    // プロフィール編集処理
    public function edit(ProfileRequest $request, AddressRequest $addressRequest)
    {
        return view('mypage.profile.edit');
    }

    // 購入したもののみ表示
    public function showBoughtItems()
    {
        // 購入したアイテムを表示する処理
        return view('mypage.bought');
    }

    // 出品したもののみ表示
    public function showSoldItems()
    {
        // 出品したアイテムを表示する処理
        return view('mypage.sold');
    }
}
