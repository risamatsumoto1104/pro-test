<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        // ユーザーの認証
        $request->authenticate();

        // プロフィールが未設定の場合、プロフィール設定ページにリダイレクト
        if (Auth::user()->profile === null) {
            return Redirect::route('mypage.profile');
        }

        // 通常のログイン後リダイレクト（必要に応じて変更）
        return Redirect::intended('/home');
    }

    /**
     * ユーザーのログアウト処理を行います。
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        // ユーザーをログアウト
        Auth::logout();
        // セッションを無効化
        $request->session()->invalidate();
        // CSRFトークンを再生成
        $request->session()->regenerateToken();

        // ログアウト後にリダイレクトするURL
        return redirect('/login');
    }
}
