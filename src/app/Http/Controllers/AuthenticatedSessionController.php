<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Verified;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    // ログイン処理（メール認証できるようにカスタマイズ）
    public function store(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        // 認証が成功した場合
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // メール認証がされていない場合
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice')
                    ->with('message', 'メール認証が必要です。メールをご確認ください。');
            }

            // メール認証済みのユーザーはホームにリダイレクト
            return redirect()->intended('/');
        }

        // 認証失敗
        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません。',
            'password' => 'ログイン情報が登録されていません。',
        ]);
    }

    // メール認証処理
    public function verify(Request $request, $user_id, $hash)
    {
        // メール認証用の URL から user_id を取得して、そのユーザーをデータベースから検索
        // ユーザーが見つからない場合、findOrFail メソッドは 404 エラー
        $user = User::findOrFail($user_id);

        // ハッシュが一致しない場合403エラー
        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'リンクが無効です。');
        }

        // 既に認証済みの場合
        if ($user->hasVerifiedEmail()) {
            return redirect('/')->with('status', '既に認証済みです。');
        }

        // 認証を完了
        $user->markEmailAsVerified();   //email_verified_at フィールドに現在時刻が保存
        event(new Verified($user));     //メール認証が完了したことをアプリケーションに通知するために Verified イベントが発火

        // 認証完了後にホームへリダイレクト
        return redirect('/')->with('status', 'メール認証が完了しました。');
    }

    // ログアウト
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
