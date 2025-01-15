<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use Tests\Traits\DatabaseSeedTrait;

class PaymentMethodTest extends DuskTestCase
{
    use DatabaseSeedTrait;

    // 支払方法が小計画面に即時反映されるかをテストする.
    public function test_whether_payment_method_is_reflected_immediately_on_the_small_plan_page()
    {
        // メール認証ミドルウェアを無効化
        $this->withoutMiddleware([\Illuminate\Auth\Middleware\EnsureEmailIsVerified::class]);

        // データベースのauto_incrementをリセット
        DB::table('users')->delete();
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE items AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE category_item AUTO_INCREMENT = 1;');

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test1@example.com',
            'email_verified_at' => now(), // これでメール認証済みとする
            'password' => bcrypt('password123'),
        ]);

        // トレイトメソッドを使用してシーディングを実行
        $this->seedDatabase();

        // プロフィール情報を作成
        Address::create([
            'user_id' => $user->user_id,
            'postal_code' => '111-1111',
            'address' => 'test address',
            'building' => 'test building',
        ]);

        // ユーザーにログインをする
        $this->actingAs($user);

        // テスト用として商品IDを設定
        $itemId = 1;
        Item::find($itemId);

        // 商品購入ページを開く(get)
        $response = $this->get(route('item.purchase', ['item_id' => $itemId]));
        $response->assertStatus(200);

        // Duskブラウザテストを実行
        $this->browse(function (Browser $browser) use ($itemId) {
            $browser->visit(route('item.purchase', ['item_id' => $itemId])) // 購入画面にアクセス
                ->assertSee('支払い方法を選択') // 支払い方法の選択画面を確認
                ->select('#paymentSelect', 'カード支払い') // 支払い方法を選択
                ->pause(500) // JavaScriptが反映されるのを待つ
                ->assertSeeIn('#summaryPayment', 'カード支払い'); // 小計画面に反映されていることを確認
        });
    }
}
