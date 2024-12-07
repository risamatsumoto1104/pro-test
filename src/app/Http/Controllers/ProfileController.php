<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // プロフィール画面の表示
    public function show()
    {
        return view('mypage.profile.show');
    }

    // プロフィール編集処理
    public function edit(ProfileRequest $request, AddressRequest $addressRequest)
    {
        return view('mypage.profile.edit');
    }

    // 購入したもののみ表示
    public function showBoughtItems()
    {
        return view('mypage.profiles.show', ['tab' => 'buy']);
    }

    // 出品したもののみ表示
    public function showSoldItems()
    {
        return view('mypage.profiles.show', ['tab' => 'sell']);
    }
}
