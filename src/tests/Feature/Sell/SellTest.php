<?php

namespace Tests\Feature\Sell;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Tests\Traits\DatabaseSeedTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SellTest extends TestCase
{
    use RefreshDatabase, DatabaseSeedTrait;

    // 商品出品画面にて必要な情報が保存できること
    public function test_to_see_if_the_required_listings_can_be_saved()
    {
        $this->withoutExceptionHandling(); // 例外を表示する

        // メール認証ミドルウェアを無効化
        $this->withoutMiddleware([\Illuminate\Auth\Middleware\EnsureEmailIsVerified::class]);

        // データベースをリセット
        $this->resetDatabase();

        User::create([
            'name' => 'Test User',
            'email' => 'test1@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password123'),
        ]);

        $user = User::create([
            'name' => 'MY User',
            'email' => 'mytest1@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password1234'),
        ]);

        // トレイトメソッドを使用してシーディングを実行
        $this->seedDatabase();

        // テスト用としてカテゴリーを1に設定
        $categoryId = 1;
        $category = Category::find($categoryId);

        // ユーザーにログインをする
        $this->actingAs($user);

        //  商品出品画面を開く(get)
        $response = $this->get(route('item.sell.create'));
        $response->assertStatus(200);

        // ストレージの設定を仮想的にする
        Storage::fake('public');

        // リクエストデータを作成
        $data = [
            'seller_user_id' => $user->user_id,
            'item_name' => 'My Item',
            'brand_name' => '',
            'categories' => [$categoryId],
            'price' => 1000,
            'description' => 'this is my item',
            'condition' => '良好',
            'item_image' => $image = UploadedFile::fake()->image('test.png'), // ファイルオブジェクトを送信
        ];
        // 出品リクエストを送信
        $response = $this->post(route('item.sell.store'), $data);

        // プロフィール画面にリダイレクトされることを確認
        $response->assertRedirect(route('mypage', ['tab' => 'sell']));

        // ストレージに画像が保存されているか確認
        Storage::disk('public')->assertExists($image->hashName());

        // データベースに保存された出品IDを取得
        $sellId = Item::where('seller_user_id', $user->user_id)->value('item_id');

        // データベースに出品情報が保存されていることを確認
        $this->assertDatabaseHas('items', [
            'seller_user_id' => $user->user_id,
            'item_name' => 'My Item',
            'price' => 1000,
            'description' => 'this is my item',
            'condition' => '良好',
            'item_image' => $image->hashName(),
        ]);

        // データベースに出品情報が保存されていることを確認
        $this->assertDatabaseHas('categories', [
            'category_id' => $categoryId,
            'content' => $category->content,
        ]);

        // データベースに出品情報が保存されていることを確認
        $this->assertDatabaseHas('category_item', [
            'item_id' => $sellId,
            'category_id' => $categoryId,
        ]);
    }
}
