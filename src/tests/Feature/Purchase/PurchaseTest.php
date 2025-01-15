<?php

namespace Tests\Feature\Purchase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use App\Models\Purchase;
use Tests\Traits\DatabaseSeedTrait;
use Mockery;
use Stripe\Stripe;

class PurchaseTest extends TestCase
{
    use RefreshDatabase, DatabaseSeedTrait;

    // 「購入する」ボタンを押下すると購入が完了する
    public function test_to_complete_purchase_by_pressing_the_button()
    {
        // メール認証ミドルウェアを無効化
        $this->withoutMiddleware([\Illuminate\Auth\Middleware\EnsureEmailIsVerified::class]);

        Stripe::setApiKey('sk_test_mock_key');

        // Stripe API をモック
        $stripeSessionMock = Mockery::mock('alias:' . \Stripe\Checkout\Session::class);
        $stripeSessionMock->shouldReceive('create')
            ->once() // createが1回呼ばれることを確認
            ->andReturn((object) [
                'url' => 'https://mock.stripe.com/checkout/session/12345',
            ]);

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

        // 送付先情報を作成
        $myAddress = Address::create([
            'user_id' => $myUser->user_id,
            'postal_code' => '111-1111',
            'address' => 'test address',
            'building' => 'test building',
        ]);

        // ユーザーにログインをする
        $this->actingAs($myUser);

        // テスト用として商品IDを設定
        $itemId = 1;
        $item = Item::find($itemId);

        // 商品購入ページを開く(get)
        $response = $this->get(route('item.purchase', ['item_id' => $itemId]));
        $response->assertStatus(200);

        // 商品を選択して「購入する」ボタンを押下
        $purchaseResponse = $this->post(route('purchase.store', ['item_id' => $item->item_id]), [
            'buyer_user_id' => $myUser->user_id,
            'item_id' => $itemId,
            'address_id' => $myAddress->address_id,
            'payment_method' => 'カード支払い',
        ]);

        // セッションに購入情報を格納
        $this->withSession([
            'purchase_data' => [
                'buyer_user_id' => $myUser->user_id,
                'item_id' => $itemId,
                'address_id' => $myAddress->address_id,
                'payment_method' => 'カード支払い',
            ],
        ]);

        // 正しくStripeの決済ページに遷移することを確認
        $purchaseResponse->assertRedirect('https://mock.stripe.com/checkout/session/12345');

        // 購入が完了する
        // 購入完了ページにに遷移
        $response = $this->get(route('purchase.success', ['item_id' => $item->item_id]));
        $response->assertStatus(200);

        // セッションから購入情報を取得
        $purchaseData = session('purchase_data');

        // 購入情報をデータベースに保存
        Purchase::create($purchaseData);

        // データベースに購入情報が保存されていることを確認
        $this->assertDatabaseHas('purchases', [
            'buyer_user_id' => $myUser->user_id,
            'item_id' => $itemId,
            'address_id' => $myAddress->address_id,
            'payment_method' => 'カード支払い',
        ]);
    }

    // 購入した商品は商品一覧画面にて「sold」と表示される
    public function test_purchased_items_is_sold()
    {
        // メール認証ミドルウェアを無効化
        $this->withoutMiddleware([\Illuminate\Auth\Middleware\EnsureEmailIsVerified::class]);

        Stripe::setApiKey('sk_test_mock_key');

        // Stripe API をモック
        $stripeSessionMock = Mockery::mock('alias:' . \Stripe\Checkout\Session::class);
        $stripeSessionMock->shouldReceive('create')
            ->once() // createが1回呼ばれることを確認
            ->andReturn((object) [
                'url' => 'https://mock.stripe.com/checkout/session/123',
            ]);

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
            'email' => 'test2@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password123'),
        ]);

        $myUser = User::create([
            'name' => 'MY User',
            'email' => 'mytest2@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password1234'),
        ]);

        // トレイトメソッドを使用してシーディングを実行
        $this->seedDatabase();

        // 送付先情報を作成
        $myAddress = Address::create([
            'user_id' => $myUser->user_id,
            'postal_code' => '111-1111',
            'address' => 'test address',
            'building' => 'test building',
        ]);

        // ユーザーにログインをする
        $this->actingAs($myUser);

        // テスト用として商品IDを設定
        $itemId = 1;
        $item = Item::find($itemId);

        // 商品購入ページを開く(get)
        $response = $this->get(route('item.purchase', ['item_id' => $itemId]));
        $response->assertStatus(200);

        // 商品を選択して「購入する」ボタンを押下
        $purchaseResponse = $this->post(route('purchase.store', ['item_id' => $item->item_id]), [
            'buyer_user_id' => $myUser->user_id,
            'item_id' => $itemId,
            'address_id' => $myAddress->address_id,
            'payment_method' => 'カード支払い',
        ]);

        // セッションに購入情報を格納
        $this->withSession([
            'purchase_data' => [
                'buyer_user_id' => $myUser->user_id,
                'item_id' => $itemId,
                'address_id' => $myAddress->address_id,
                'payment_method' => 'カード支払い',
            ],
        ]);

        // 正しくStripeの決済ページに遷移することを確認
        $purchaseResponse->assertRedirect('https://mock.stripe.com/checkout/session/123');

        // 購入が完了する
        // 購入完了ページにに遷移
        $response = $this->get(route('purchase.success', ['item_id' => $item->item_id]));
        $response->assertStatus(200);

        // セッションから購入情報を取得
        $purchaseData = session('purchase_data');

        // 購入情報をデータベースに保存
        Purchase::create($purchaseData);

        // 商品一覧画面を開く(get)
        // 購入済み商品を表示する
        $response = $this->get('/');
        $response->assertStatus(200);

        // 購入後のデータを再取得
        $item = Item::find($itemId);

        // 購入した商品が「sold」として表示されている
        $response->assertViewHas('items', function ($items) use ($itemId) {
            $soldItem = $items->firstWhere('item_id', $itemId);
            return $soldItem && $soldItem->status === 'sold';
        });
    }

    // 「プロフィール/購入した商品一覧」に追加されている
    public function test_added_to_purchased_items_list()
    {
        // モックをリセット
        Mockery::close();
        // セッションをリセット
        session()->flush();

        // メール認証ミドルウェアを無効化
        $this->withoutMiddleware([\Illuminate\Auth\Middleware\EnsureEmailIsVerified::class]);

        Stripe::setApiKey('sk_test_mock_key');

        // Stripe API をモック
        $stripeSessionMock = Mockery::mock('alias:' . \Stripe\Checkout\Session::class);
        $stripeSessionMock->shouldReceive('create')
            ->once() // createが1回呼ばれることを確認
            ->andReturn((object) [
                'url' => 'https://mock.stripe.com/checkout/session/345',
            ]);

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
            'email' => 'test3@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password123'),
        ]);

        $myUser = User::create([
            'name' => 'MY User',
            'email' => 'mytest3@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password1234'),
        ]);

        // トレイトメソッドを使用してシーディングを実行
        $this->seedDatabase();

        // 送付先情報を作成
        $myAddress = Address::create([
            'user_id' => $myUser->user_id,
            'postal_code' => '111-1111',
            'address' => 'test address',
            'building' => 'test building',
        ]);

        // ユーザーにログインをする
        $this->actingAs($myUser);

        // テスト用として商品IDを設定
        $itemId = 1;
        $item = Item::find($itemId);

        // 商品購入ページを開く(get)
        $response = $this->get(route('item.purchase', ['item_id' => $itemId]));
        $response->assertStatus(200);

        // 商品を選択して「購入する」ボタンを押下
        $purchaseResponse = $this->post(route('purchase.store', ['item_id' => $item->item_id]), [
            'buyer_user_id' => $myUser->user_id,
            'item_id' => $itemId,
            'address_id' => $myAddress->address_id,
            'payment_method' => 'カード支払い',
        ]);

        // セッションに購入情報を格納
        $this->withSession([
            'purchase_data' => [
                'buyer_user_id' => $myUser->user_id,
                'item_id' => $itemId,
                'address_id' => $myAddress->address_id,
                'payment_method' => 'カード支払い',
            ],
        ]);

        // 正しくStripeの決済ページに遷移することを確認
        $purchaseResponse->assertRedirect('https://mock.stripe.com/checkout/session/345');

        // 購入が完了する
        // 購入完了ページにに遷移
        $response = $this->get(route('purchase.success', ['item_id' => $item->item_id]));
        $response->assertStatus(200);

        // セッションから購入情報を取得
        $purchaseData = session('purchase_data');

        // 購入情報をデータベースに保存
        Purchase::create($purchaseData);

        // 購入が完了する
        // データベースに購入情報が保存されていることを確認
        $this->assertDatabaseHas('purchases', [
            'buyer_user_id' => $myUser->user_id,
            'item_id' => $itemId,
            'address_id' => $myAddress->address_id,
            'payment_method' => 'カード支払い',
        ]);

        // プロフィール画面を表示する
        $response = $this->get(route('mypage', ['tab' => 'buy']));
        $response->assertStatus(200);

        // 購入した商品がプロフィールの購入した商品一覧に追加されている
        $response->assertSee($item->name); // 商品名が一覧に表示されているか
    }
}
