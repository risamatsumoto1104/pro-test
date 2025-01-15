<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use App\Models\Purchase;
use App\Models\Profile;
use Tests\Traits\DatabaseSeedTrait;

class UserTest extends TestCase
{
    use RefreshDatabase, DatabaseSeedTrait;

    // 必要な情報が取得できる
    public function test_that_allow_users_is_get_the_information_they_need()
    {
        // メール認証ミドルウェアを無効化
        $this->withoutMiddleware([\Illuminate\Auth\Middleware\EnsureEmailIsVerified::class]);

        // 外部キー制約を無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // テーブルのデータをリセット（truncate）
        DB::table('users')->truncate();
        DB::table('categories')->truncate();
        DB::table('items')->truncate();
        DB::table('category_item')->truncate();
        DB::table('purchases')->truncate();
        DB::table('address_item')->truncate();
        DB::table('addresses')->truncate();

        // 外部キー制約を再度有効化
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // auto_incrementをリセット
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE items AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE category_item AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE purchases AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE address_item AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE addresses AUTO_INCREMENT = 1;');

        User::create([
            'name' => 'Test User',
            'email' => 'test1@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password123'),
        ]);

        $myUser = User::create([
            'name' => 'MY User',
            'email' => 'mytest1@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password1234'),
        ]);

        // トレイトメソッドを使用してシーディングを実行
        $this->seedDatabase();

        // プロフィール情報を作成
        $myProfile = Profile::create([
            'user_id' => $myUser->user_id,
            'profile_image' => 'default-profile.png',
            'postal_code' => '111-1111',
            'address' => 'test address',
            'building' => 'test building',
        ]);

        // 送付先情報を作成
        $myAddress = Address::create([
            'user_id' => $myUser->user_id,
            'postal_code' => $myProfile->postal_code,
            'address' => $myProfile->address,
            'building' => $myProfile->building,
        ]);

        // 購入情報を作成
        $myPurchase = Purchase::create([
            'buyer_user_id' => $myUser->user_id,
            'item_id' => 1,
            'address_id' => $myAddress->address_id,
            'payment_method' => 'カード支払い',
        ]);

        // 自分の出品アイテムの作成
        $mySellItem = Item::create([
            'seller_user_id' => $myUser->user_id,
            'item_name' => 'My Item',
            'price' => 1000,
            'description' => 'this is my item',
            'condition' => '良好',
            'item_image' => 'HDD.jpg', //すでに入ってある商品を使用
        ]);

        // ユーザーにログインをする
        $this->actingAs($myUser);

        // プロフィールページを開く(get)　デフォルトはsellタブ
        $response = $this->get(route('mypage'));
        $response->assertStatus(200);

        // プロフィール画像、ユーザー名、出品した商品が表示されている
        $response->assertSee($myProfile->profile_image);
        $response->assertSee($myUser->name);
        $response->assertSee($mySellItem->item_name);

        // プロフィール画面を表示する(購入した商品一覧を選択)
        $response = $this->get(route('mypage', ['tab' => 'buy']));
        $response->assertStatus(200);

        // 購入した商品が表示されている
        $response->assertSee($myPurchase->item->item_name);
    }

    // 変更項目が初期値として過去設定されていること
    public function test_to_ensure_that_the_initial_value_is_set_for_the_change_item()
    {
        // メール認証ミドルウェアを無効化
        $this->withoutMiddleware([\Illuminate\Auth\Middleware\EnsureEmailIsVerified::class]);

        // 外部キー制約を無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // テーブルのデータをリセット（truncate）
        DB::table('users')->truncate();
        DB::table('categories')->truncate();
        DB::table('items')->truncate();
        DB::table('category_item')->truncate();
        DB::table('purchases')->truncate();
        DB::table('address_item')->truncate();
        DB::table('addresses')->truncate();

        // 外部キー制約を再度有効化
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // auto_incrementをリセット
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE items AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE category_item AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE purchases AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE address_item AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE addresses AUTO_INCREMENT = 1;');

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password123'),
        ]);

        // トレイトメソッドを使用してシーディングを実行
        $this->seedDatabase();

        // プロフィール情報を作成
        $myProfile = Profile::create([
            'user_id' => $user->user_id,
            'profile_image' => 'default-profile.png',
            'postal_code' => '111-1111',
            'address' => 'test address',
            'building' => 'test building',
        ]);

        // ユーザーにログインをする
        $this->actingAs($user);

        // プロフィールページを開く(get)
        $response = $this->get(route('mypage'));
        $response->assertStatus(200);

        // プロフィール編集画面を開く(get)
        $response = $this->get(route('mypage.profile'));
        $response->assertStatus(200);

        // 各項目の初期値が正しく表示されている
        $response->assertSee($myProfile->profile_image);
        $response->assertSee($myProfile->postal_code);
        $response->assertSee($myProfile->address);
        $response->assertSee($myProfile->building);
    }
}
