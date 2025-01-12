<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    // ログアウトができる
    public function test_user_can_logout()
    {
        // データベースのauto_incrementをリセット
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test1@example.com',
            'password' => bcrypt('password123'),
        ]);

        // ユーザーにログインをする
        $this->actingAs($user);

        // ログアウトボタンを押す(post)
        $response = $this->post('/logout');

        // ログアウト後にログインページにリダイレクトされることを確認
        $response->assertRedirect('/login');
    }
}
