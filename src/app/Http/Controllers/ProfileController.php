<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        return view('mypage.profile.show');
    }

    // プロフィール編集処理
    public function edit(Request $request)
    {
        return view('mypage.profile.edit');
    }

    public function showBoughtItems()
    {
        return view('mypage.profiles.show', ['tab' => 'buy']);
    }

    public function showSoldItems()
    {
        return view('mypage.profiles.show', ['tab' => 'sell']);
    }
}
