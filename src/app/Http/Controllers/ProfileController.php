<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        return view('mypage.profiles.show');
    }

    public function edit(Request $request)
    {
        // プロフィール編集処理
        return view('/mypage');
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
