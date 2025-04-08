<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        // ユーザー作成
        $user = app(CreateNewUser::class)->create($request->all());

        // ユーザーをログイン状態にする
        Auth::login($user);

        // プロフィール設定ページにリダイレクト
        return Redirect::route('mypage.profile');
    }
}
