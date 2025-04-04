<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    // チャット取引画面の表示
    public function show(Request $request)
    {
        return view('mypage.chat.show');
    }
}
