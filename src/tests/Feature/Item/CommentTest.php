<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Tests\Traits\DatabaseSeedTrait;

class CommentTest extends TestCase
{
    use RefreshDatabase, DatabaseSeedTrait;

    // ログイン済みのユーザーはコメントを送信できる
    public function logged_id_users_can_submit_comments()
    {
        // メール認証ミドルウェアを無効化
        $this->withoutMiddleware([\Illuminate\Auth\Middleware\EnsureEmailIsVerified::class]);

        // データベースをリセット
        $this->resetDatabase();

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test1@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password123'),
        ]);

        // トレイトメソッドを使用してシーディングを実行
        $this->seedDatabase();

        // テスト用として商品IDを設定
        $itemId = 1;
        $item = Item::find($itemId);

        // ユーザーにログインをする
        $this->actingAs($user);

        // 商品詳細ページを開く(get)
        $response = $this->get(route('items.show', ['item_id' => $itemId]));
        $response->assertStatus(200);

        // 初期状態でコメント数が0であることを確認
        $this->assertEquals(0, $item->comments_count);
        $response->assertSee('0');

        // コメントを入力する
        // コメントボタンを押す
        $commentResponse = $this->post(route('item.comment', ['item_id' => $item->item_id]), [
            'comment' => 'new comment', // コメント内容
        ]);
        $commentResponse->assertStatus(200);

        // コメントが保存され、コメント数が増加する
        $this->assertEquals(1, $item->comments_count); // コメント数が1に増加していることを確認

        // コメントが詳細ページに表示されていることを確認
        $response = $this->get(route('items.show', ['item_id' => $itemId]));
        $response->assertSee('new comment');
    }

    // ログイン前のユーザーはコメントを送信できない
    public function users_cannot_submit_comments_before_logging_in()
    {
        // メール認証ミドルウェアを無効化
        $this->withoutMiddleware([\Illuminate\Auth\Middleware\EnsureEmailIsVerified::class]);

        // データベースをリセット
        $this->resetDatabase();

        User::create([
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password123'),
        ]);

        // トレイトメソッドを使用してシーディングを実行
        $this->seedDatabase();

        // テスト用として商品IDを設定
        $itemId = 1;
        $item = Item::find($itemId);

        // 商品詳細ページを開く(get)
        $response = $this->get(route('items.show', ['item_id' => $itemId]));
        $response->assertStatus(200);

        // コメントを入力する
        // コメントボタンを押す
        $response = $this->post(route('item.comment', ['item_id' => $item->item_id]), [
            'comment' => 'new comment', // コメント内容
        ]);

        // コメントが送信されない
        // ログイン画面にリダイレクトする
        $response->assertRedirect(route('login'));

        // コメントがデータベースに保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'content' => 'Test comment',
        ]);
    }

    // コメントが入力されていない場合、バリデーションメッセージが表示される
    public function test_comment_is_required()
    {
        // メール認証ミドルウェアを無効化
        $this->withoutMiddleware([\Illuminate\Auth\Middleware\EnsureEmailIsVerified::class]);

        // データベースをリセット
        $this->resetDatabase();

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test3@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password123'),
        ]);

        // トレイトメソッドを使用してシーディングを実行
        $this->seedDatabase();

        // テスト用として商品IDを設定
        $itemId = 1;
        $item = Item::find($itemId);

        // ユーザーにログインをする
        $this->actingAs($user);

        // 商品詳細ページを開く(get)
        $response = $this->get(route('items.show', ['item_id' => $itemId]));
        $response->assertStatus(200);

        // コメントボタンを押す
        $response = $this->post(route('item.comment', ['item_id' => $item->item_id]));

        // 商品詳細ページにリダイレクトされ、バリデーションメッセージが表示される
        $response->assertRedirect(route('items.show', ['item_id' => $itemId]));
        $response->assertSessionHasErrors([
            'comment' => '商品コメントを入力してください。',
        ]);
    }

    // コメントが255字以上の場合、バリデーションメッセージが表示される
    public function test_comment_is_max_255()
    {
        // メール認証ミドルウェアを無効化
        $this->withoutMiddleware([\Illuminate\Auth\Middleware\EnsureEmailIsVerified::class]);

        // データベースをリセット
        $this->resetDatabase();

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test4@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password123'),
        ]);

        // トレイトメソッドを使用してシーディングを実行
        $this->seedDatabase();

        // テスト用として商品IDを設定
        $itemId = 1;
        $item = Item::find($itemId);

        // ユーザーにログインをする
        $this->actingAs($user);

        // 商品詳細ページを開く(get)
        $response = $this->get(route('items.show', ['item_id' => $itemId]));
        $response->assertStatus(200);

        // 256文字以上のコメントを入力する
        $longComment = str_repeat('a', 256);

        // コメントボタンを押す
        $response = $this->post(route('item.comment', ['item_id' => $item->item_id]), [
            'comment' => $longComment, // コメント内容
        ]);

        // 商品詳細ページにリダイレクトされ、バリデーションメッセージが表示される
        $response->assertRedirect(route('items.show', ['item_id' => $itemId]));
        $response->assertSessionHasErrors([
            'comment' => '商品コメントは255文字以内で入力してください。',
        ]);
    }
}
