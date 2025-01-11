<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use App\Http\Requests\LoginRequest;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 新規ユーザーの登録処理
        Fortify::createUsersUsing(CreateNewUser::class);

        // GETメソッドで/registerにアクセスした時に表示するviewファイル
        Fortify::registerView(function () {
            return view('auth.register');
        });

        // GETメソッドで/loginにアクセスしたときに表示するviewファイル
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // login処理の実行回数を1分あたり10回までに制限
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(10)->by($email . $request->ip());
        });

        // GETメソッドで/email/verifyにアクセスしたときに表示するviewファイル
        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        // FortifyLoginRequest を LoginRequest に置き換える。
        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);
    }
}
