<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use Tests\Traits\DatabaseSeedTrait;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase, DatabaseSeedTrait;

    // 「商品名」で部分一致検索ができる
    public function test_can_search_for_partial_matches_by_item_name()
    {
        // データベースのauto_incrementをリセット
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE items AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE category_item AUTO_INCREMENT = 1;');

        User::create([
            'name' => 'Test User',
            'email' => 'test1@example.com',
            'password' => bcrypt('password123'),
        ]);

        // トレイトメソッドを使用してシーディングを実行
        $this->seedDatabase();

        // ホームページで商品を検索
        // 検索ボタンを押す
        $keyword = '時計';
        $response = $this->get('/search?keyword=' . $keyword . '&tab=recommend');

        // 検索結果を取得
        $searchResults = Item::where('item_name', 'like', '%' . $keyword . '%')->get();

        // 部分一致する商品が表示される
        foreach ($searchResults as $item) {
            $response->assertSee($item->item_name);
        }
    }

    // 検索状態がマイリストでも保持されている
    public function test_search_state_can_be_maintained_in_my_list()
    {
        // データベースのauto_incrementをリセット
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE items AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE category_item AUTO_INCREMENT = 1;');

        User::create([
            'name' => 'Test User',
            'email' => 'test2@example.com',
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

        // 商品をいいねする(腕時計はitem_id=1)
        Like::create([
            'user_id' => $myUser->user_id,
            'item_id' => 1,
        ]);

        // ホームページで商品を検索
        // 検索ボタンを押す
        $keyword = '時計';
        $response = $this->get('/search?keyword=' . $keyword . '&tab=recommend');

        // 検索結果を取得
        $searchResults = Item::where('item_name', 'like', '%' . $keyword . '%')->get();

        // 部分一致する商品が表示される
        foreach ($searchResults as $item) {
            $response->assertSee($item->item_name);
        }

        // マイリストページに遷移
        $response = $this->get('/?tab=mylist&keyword=' . $keyword);

        // マイリストにて検索キーワードが保持されている
        foreach ($searchResults as $item) {
            $response->assertSee($item->item_name);
        }
    }
}
