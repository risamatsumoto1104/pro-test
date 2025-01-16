<?php

namespace Tests\Feature\Purchase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use App\Models\Purchase;
use App\Models\Profile;
use Tests\Traits\DatabaseSeedTrait;
use Mockery;
use Stripe\Stripe;

class AddressTest extends TestCase
{
    use RefreshDatabase, DatabaseSeedTrait;

    // 送付先住所変更画面にて登録した住所が商品購入画面に反映されている
    public function test_that_the_changed_shipping_address_is_reflected_on_the_purchase_screen()
    {
        // メール認証ミドルウェアを無効化
        $this->withoutMiddleware([\Illuminate\Auth\Middleware\EnsureEmailIsVerified::class]);

        // データベースをリセット
        $this->resetDatabase();

        User::create([
            'name' => 'Test User',
            'email' => 'test4@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password123'),
        ]);

        $myUser = User::create([
            'name' => 'MY User',
            'email' => 'mytest4@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password1234'),
        ]);

        // トレイトメソッドを使用してシーディングを実行
        $this->seedDatabase();

        // プロフィール情報を作成
        $myProfile = Profile::create([
            'user_id' => $myUser->user_id,
            'postal_code' => '111-1111',
            'address' => 'test address',
            'building' => 'test building',
        ]);

        // ユーザーにログインをする
        $this->actingAs($myUser);

        // テスト用として商品IDを設定
        $itemId = 1;
        Item::find($itemId);

        // 商品購入ページを開く(get)
        $response = $this->get(route('item.purchase', ['item_id' => $itemId]));
        $response->assertStatus(200);

        // 送付先住所変更画面を開く
        $response = $this->get(route('purchase.address.edit', ['item_id' => $itemId]));
        $response->assertStatus(200);

        // Addressテーブルに保存
        $myAddress = Address::create([  // Addressテーブルに新しい住所を保存
            'user_id' => $myUser->user_id,  // ユーザーIDを関連付け
            'postal_code' => $myProfile->postal_code,  // プロフィールから住所を取得
            'address' => $myProfile->address,  // プロフィールから住所を取得
            'building' => $myProfile->building,  // プロフィールから住所を取得
        ]);

        // address_item テーブルで紐付けを更新
        DB::table('address_item')->insert([
            'item_id' => $itemId,
            'address_id' => $myAddress->address_id,
        ]);

        // 商品購入画面を再度開く
        $response = $this->get(route('item.purchase', ['item_id' => $itemId]));
        $response->assertStatus(200);

        // 登録した住所が商品購入画面に正しく反映される
        $response->assertSee('111-1111');
        $response->assertSee('test address');
        $response->assertSee('test building');
    }

    // 購入した商品に送付先住所が紐づいて登録される
    public function test_to_see_if_a_shipping_address_is_associated_with_a_purchased_item()
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

        // データベースをリセット
        $this->resetDatabase();

        User::create([
            'name' => 'Test User',
            'email' => 'test5@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password123'),
        ]);

        $myUser = User::create([
            'name' => 'MY User',
            'email' => 'mytest5@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password1234'),
        ]);

        // トレイトメソッドを使用してシーディングを実行
        $this->seedDatabase();

        // プロフィール情報を作成
        $myProfile = Profile::create([
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

        // 送付先住所変更画面を開く
        $response = $this->get(route('purchase.address.edit', ['item_id' => $itemId]));
        $response->assertStatus(200);

        // Addressテーブルに保存
        $myAddress = Address::create([  // Addressテーブルに新しい住所を保存
            'user_id' => $myUser->user_id,  // ユーザーIDを関連付け
            'postal_code' => $myProfile->postal_code,  // プロフィールから住所を取得
            'address' => $myProfile->address,  // プロフィールから住所を取得
            'building' => $myProfile->building,  // プロフィールから住所を取得
        ]);

        // address_item テーブルで紐付けを更新
        DB::table('address_item')->insert([
            'item_id' => $itemId,
            'address_id' => $myAddress->address_id,
        ]);

        // 商品購入画面を再度開く
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

        // 正しく送付先住所が紐づいている
        // addressItemテーブル（中間テーブル）のitem_idと一致しているか確認
        $this->assertDatabaseHas('address_item', [
            'item_id' => $itemId,
            'address_id' => $myAddress->address_id,
        ]);

        // address_itemテーブル内で$purchase->item_idと一致するものがあるか確認
        $this->assertTrue(
            DB::table('address_item')->where('item_id', $itemId)->exists(),
            "Item ID does not match the address item entry"
        );
    }
}
