<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Tests\Traits\DatabaseSeedTrait;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase, DatabaseSeedTrait;

    // 必要な情報が表示される
    public function test_can_view_the_details_of_the_item_need()
    {
        // データベースをリセット
        $this->resetDatabase();

        User::create([
            'name' => 'Test User',
            'email' => 'test1@example.com',
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

        // 商品詳細ページに情報が表示されているかを確認
        $response->assertSee($item->item_image); //商品画像
        $response->assertSee($item->item_name); //商品名
        $response->assertSee($item->brand_name); //ブランド名
        $response->assertSee(number_format($item->price)); //価格
        $response->assertSee($item->itemLikes()->count()); // いいね数
        $response->assertSee($item->comments_count); // コメント数
        $response->assertSee($item->description); //商品説明
        $response->assertSee($item->condition); //商品の状態

        foreach ($item->categories as $category) {
            $response->assertSee($category->content); // カテゴリー
        }

        foreach ($item->comments as $comment) {
            $response->assertSee($comment->user->profile ? $comment->user->profile->profile_image : 'default-profile.png'); // コメントしたユーザー画像
            $response->assertSee($comment->user->name); // コメントしたユーザー名
            $response->assertSee($comment->content); //コメント内容
        }
    }

    // 複数選択されたカテゴリが表示されているか
    public function test_multiple_selected_categories_can_be_displayed()
    {
        // データベースをリセット
        $this->resetDatabase();

        User::create([
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'password' => bcrypt('password123'),
        ]);

        // トレイトメソッドを使用してシーディングを実行
        $this->seedDatabase();

        // テスト用として商品IDを設定（商品1はカテゴリーを3つ紐付けている）
        $itemId = 1;
        $item = Item::find($itemId);

        // 商品詳細ページを開く(get)
        $response = $this->get(route('items.show', ['item_id' => $itemId]));
        $response->assertStatus(200);

        // 複数のカテゴリが正しく表示されているか確認
        foreach ($item->categories as $category) {
            $response->assertSee($category->content, false);
        }
    }
}
