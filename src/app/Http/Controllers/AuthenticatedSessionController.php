<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
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
