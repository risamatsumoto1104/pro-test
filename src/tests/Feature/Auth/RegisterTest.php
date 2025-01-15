<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    // 名前が入力されていない場合、バリデーションメッセージが表示される
    public function test_name_is_required()
    {
        // 会員登録ページを開く (get)
        $response = $this->get('/register');
        $response->assertStatus(200);

        // 名前を入力せずに他の必要項目を入力する
        // 登録ボタンを押す(post)
        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 「お名前を入力してください」というバリデーションメッセージが表示される
        $response->assertSessionHasErrors(['name' => 'お名前を入力してください']);
    }

    // メールアドレスが入力されていない場合、バリデーションメッセージが表示される
    public function test_email_is_required()
    {
        // 会員登録ページを開く (get)
        $response = $this->get('/register');
        $response->assertStatus(200);

        // メールアドレスを入力せずに他の必要項目を入力する
        // 登録ボタンを押す(post)
        $response = $this->post('/register', [
            'name' => 'Test User',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 「メールアドレスを入力してください」というバリデーションメッセージが表示される
        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    // パスワードが入力されていない場合、バリデーションメッセージが表示される
    public function test_password_is_required()
    {
        // 会員登録ページを開く (get)
        $response = $this->get('/register');
        $response->assertStatus(200);

        // パスワードを入力せずに他の必要項目を入力する
        // 登録ボタンを押す(post)
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 「パスワードを入力してください」というバリデーションメッセージが表示される
        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    // パスワードが7文字以下の場合、バリデーションメッセージが表示される
    public function test_password_is_min_8()
    {
        // 会員登録ページを開く (get)
        $response = $this->get('/register');
        $response->assertStatus(200);

        // 7文字以下のパスワードと他の必要項目を入力する
        // 登録ボタンを押す(post)
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'pass',
            'password_confirmation' => 'pass',
        ]);

        // 「パスワードは8文字以上で入力してください」というバリデーションメッセージが表示される
        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    // パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される
    public function test_password_confirmation_is_must_match()
    {

        // 会員登録ページを開く (get)
        $response = $this->get('/register');
        $response->assertStatus(200);

        // 確認用パスワードと異なるパスワードを入力し、他の必要項目も入力する
        // 登録ボタンを押す(post)
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ]);

        // 「パスワードと一致しません」というバリデーションメッセージが表示される
        $response->assertSessionHasErrors(['password' => 'パスワードと一致しません']);
    }

    // 全ての項目が入力されている場合、会員情報が登録され、プロフィール設定画面に遷移される
    public function test_user_can_register_success()
    {

        // 会員登録ページを開く (get)
        $response = $this->get('/register');
        $response->assertStatus(200);

        // 全ての必要項目を正しく入力する
        // 登録ボタンを押す(post)
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // データベースに登録されていることを確認
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        // プロフィール設定画面にリダイレクトされることを確認
        $response->assertRedirect('/mypage/profile');
    }
}
