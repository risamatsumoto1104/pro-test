<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Address;
use App\Models\Like;
use Tests\Traits\DatabaseSeedTrait;

class MyListTest extends TestCase
{
    use RefreshDatabase, DatabaseSeedTrait;

    // マイリスト一覧にていいねした商品だけが表示される
    public function test_user_can_get_only_items_liked()
    {
        // データベースのauto_incrementをリセット
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE items AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE category_item AUTO_INCREMENT = 1;');

        User::create([
            'name' => 'Test User',
            'email' => 'test1@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password123'),
        ]);

        $myUser = User::create([
            'name' => 'MY User',
            'email' => 'mytest@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password1234'),
        ]);

        // トレイトメソッドを使用してシーディングを実行
        $this->seedDatabase();

        // ユーザーにログインをする
        $this->actingAs($myUser);

        // 商品をいいねする
        Like::create([
            'user_id' => $myUser->user_id,
            'item_id' => 1,
        ]);

        // マイリスト一覧を開く(get)
        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

        // ユーザーがいいねした商品のIDを取得
        $likedItemIds = Like::where('user_id', $myUser->user_id)->pluck('item_id')->toArray();

        // いいねをした商品が表示される
        $response->assertViewHas('items', function ($items) use ($likedItemIds) {
            return $items->count() == 1 && $items->pluck('item_id')->contains($likedItemIds[0]);
        });
    }

    // マイリスト一覧にて購入済み商品は「Sold」と表示される
    public function test_purchased_items_is_sold()
    {
        // データベースのauto_incrementをリセット
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE items AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE category_item AUTO_INCREMENT = 1;');

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password123'),
        ]);

        // トレイトメソッドを使用してシーディングを実行
        $this->seedDatabase();

        // ユーザーにログインをする
        $this->actingAs($user);

        // 送付先情報を作成
        Address::create([
            'user_id' => 1,
            'postal_code' => '111-1111',
            'address' => 'test address',
            'building' => 'test building',
        ]);

        $itemId = 1;

        // 商品をいいねする
        Like::create([
            'user_id' => $user->user_id,
            'item_id' => $itemId,
        ]);

        // 購入情報を作成
        Purchase::create([
            'buyer_user_id' => 1,
            'item_id' => $itemId,
            'address_id' => 1,
            'payment_method' => 'カード支払い',
        ]);

        // マイリスト一覧を開く(get)
        // (いいねされた）購入済み商品を表示する
        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

        // 購入済み商品に「Sold」のラベルが表示される
        $response->assertViewHas('items', function ($items) use ($itemId) {
            $soldItem = $items->firstWhere('item_id', $itemId);
            return $soldItem && $soldItem->status === 'sold';
        });
    }

    // マイリスト一覧にて自分が出品した商品は表示されない
    public function test_my_item_is_not_listed()
    {
        // データベースのauto_incrementをリセット
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE items AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE category_item AUTO_INCREMENT = 1;');

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test3@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password123'),
        ]);

        // トレイトメソッドを使用してシーディングを実行
        $this->seedDatabase();

        // 自分の出品アイテムの作成
        $mySellItem = Item::create([
            'seller_user_id' => $user->user_id,
            'item_name' => 'My Item',
            'price' => 1000,
            'description' => 'this is my item',
            'condition' => '良好',
            'item_image' => 'HDD.jpg', //すでに入ってある商品画像を使用
        ]);

        // 再度、作成したアイテムをデータベースから取得
        $mySellItem = Item::find($mySellItem->item_id);

        // ユーザーにログインをする
        $this->actingAs($user);

        // マイリスト一覧を開く(get)
        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

        // 自分が出品した商品が一覧に表示されない
        $response->assertViewHas('items', function ($items) use ($mySellItem) {
            return !$items->contains(function ($item) use ($mySellItem) {
                return $item->item_id === $mySellItem->item_id;
            });
        });
    }

    // 未認証の場合は何も表示されない
    public function items_cannot_be_displayed_without_authentication()
    {
        // データベースのauto_incrementをリセット
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE items AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE category_item AUTO_INCREMENT = 1;');

        // トレイトメソッドを使用してシーディングを実行
        $this->seedDatabase();

        // ログインしないままマイリスト一覧を開く(get)
        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

        // 商品が何も表示されない（ログインしてくださいと表示される）
        $response->assertSee('ログインしてください');
        $response->assertDontSee('Item 1');
    }
}
