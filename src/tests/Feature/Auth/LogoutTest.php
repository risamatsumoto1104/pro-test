<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    // ログアウトができる
    public function test_user_can_logout()
    {
        // データベースをリセット
        $this->resetDatabase();

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
