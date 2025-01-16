<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Tests\Traits\DatabaseSeedTrait;

class LikeTest extends TestCase
{
    use RefreshDatabase, DatabaseSeedTrait;

    // いいねアイコンを押下することによって、いいねした商品として登録することができる。
    public function test_user_can_register_it_as_a_item_liked()
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

        // 初期状態でいいね数が0であることを確認
        $this->assertEquals(0, $item->itemLikes()->count());
        $response->assertSee('0');

        // いいねアイコンを押下
        $likeResponse = $this->post(route('item.toggleLike', ['item_id' => $item->item_id]));
        $likeResponse->assertStatus(200);

        // いいねした商品として登録され、いいね合計値が増加表示される
        $this->assertEquals(1, $item->itemLikes()->count()); // いいね数が1に増加していることを確認
    }

    // 追加済みのアイコンは色が変化する
    public function test_of_changing_color_of_like_icon()
    {
        // メール認証ミドルウェアを無効化
        $this->withoutMiddleware([\Illuminate\Auth\Middleware\EnsureEmailIsVerified::class]);

        // データベースをリセット
        $this->resetDatabase();

        $user = User::create([
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

        // ユーザーにログインをする
        $this->actingAs($user);

        // 商品詳細ページを開く(get)
        $response = $this->get(route('items.show', ['item_id' => $itemId]));
        $response->assertStatus(200);

        // アイコンが初期状態あることを確認
        $response->assertSee('/storage/icon_images/星アイコン8.png');

        // いいねアイコンを押下
        $likeResponse = $this->post(route('item.toggleLike', ['item_id' => $item->item_id]));
        $likeResponse->assertStatus(200);

        // いいねアイコンが押下された状態では色が変化する
        $response = $this->get(route('items.show', ['item_id' => $item->item_id])); // 商品詳細ページを開く(get)
        $response->assertStatus(200);
        $response->assertSee('/storage/icon_images/星アイコン_liked.png'); //アイコンの色が変化していることを確認
    }

    // 再度いいねアイコンを押下することによって、いいねを解除することができる。
    public function test_user_can_remove_the_like()
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

        // 初期状態でいいね数が0であることを確認
        $this->assertEquals(0, $item->itemLikes()->count());
        $response->assertSee('0'); // 初期のいいね数を表示

        // いいねアイコンを押下
        $likeResponse = $this->post(route('item.toggleLike', ['item_id' => $item->item_id]));
        $likeResponse->assertStatus(200);

        // いいね数が1に増加していることを確認
        $this->assertEquals(1, $item->itemLikes()->count());

        // 再度いいねを押下して解除
        $unlikeResponse = $this->post(route('item.toggleLike', ['item_id' => $item->item_id]));
        $unlikeResponse->assertStatus(200);

        // いいねが解除され、いいね合計値が減少表示される
        $this->assertEquals(0, $item->itemLikes()->count()); // いいね数が0に戻っていることを確認
        $response->assertSee('/storage/icon_images/星アイコン8.png'); // アイコンが初期状態にもどっていることを確認
    }
}
