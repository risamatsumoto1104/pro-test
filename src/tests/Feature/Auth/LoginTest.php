<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    // メールアドレスが入力されていない場合、バリデーションメッセージが表示される
    public function test_email_is_required()
    {

        // データベースのauto_incrementをリセット
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');

        User::create([
            'name' => 'Test User',
            'email' => 'test1@example.com',
            'password' => bcrypt('password123'),
        ]);

        // ログインページを開く  (get)
        $response = $this->get('/login');
        $response->assertStatus(200);

        // メールアドレスを入力せずに他の必要項目を入力する
        // ログインボタンを押す(post)
        $response = $this->post('/login', [
            'password' => 'password123',
        ]);

        // 「メールアドレスを入力してください」というバリデーションメッセージが表示される
        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    // パスワードが入力されていない場合、バリデーションメッセージが表示される
    public function test_password_is_required()
    {
        // データベースのauto_incrementをリセット
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');

        User::create([
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'password' => bcrypt('password123'),
        ]);

        // ログインページを開く  (get)
        $response = $this->get('/login');
        $response->assertStatus(200);

        // パスワードを入力せずに他の必要項目を入力する
        // ログインボタンを押す(post)
        $response = $this->post('/login', [
            'email' => 'test2@example.com',
        ]);

        // 「パスワードを入力してください」というバリデーションメッセージが表示される
        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    // 入力情報が間違っている場合、バリデーションメッセージが表示される
    public function test_login_information_is_must_match()
    {
        // データベースのauto_incrementをリセット
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');

        User::create([
            'name' => 'Test User',
            'email' => 'test3@example.com',
            'password' => bcrypt('password123'),
        ]);

        // ログインページを開く  (get)
        $response = $this->get('/login');
        $response->assertStatus(200);

        // 必要項目を登録されていない情報を入力する
        // ログインボタンを押す(post)
        $response = $this->post('/login', [
            'email' => 'different@example.com',
            'password' => 'different123',
        ]);

        // 「ログイン情報が登録されていません」というバリデーションメッセージが表示される
        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません。',
            'password' => 'ログイン情報が登録されていません。',
        ]);
    }

    // 正しい情報が入力された場合、ログイン処理が実行される
    public function test_user_can_register_success()
    {
        // データベースのauto_incrementをリセット
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');

        User::create([
            'name' => 'Test User',
            'email' => 'test4@example.com',
            'password' => bcrypt('password123'),
        ]);

        // ログインページを開く  (get)
        $response = $this->get('/login');
        $response->assertStatus(200);

        // 全ての必要項目を正しく入力する
        // ログインボタンを押す(post)
        $response = $this->post('/login', [
            'email' => 'test4@example.com',
            'password' => 'password123',
        ]);

        // 登録したユーザーでログイン
        $user = User::where('email', 'test4@example.com')->first();
        // データベースからユーザーを取得して認証されていることを確認
        $this->assertAuthenticatedAs($user);
    }
}
