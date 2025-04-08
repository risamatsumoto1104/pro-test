<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\DB;

trait DatabaseSeedTrait
{
    // ダミーデータを作成する共通メソッド
    public function seedDatabase()
    {
        $categories = [
            'ファッション',
            '家電',
            'インテリア',
            'レディース',
            'メンズ',
            'コスメ',
            '本',
            'ゲーム',
            'スポーツ',
            'キッチン',
            'ハンドメイド',
            'アクセサリー',
            'おもちゃ',
            'ベビー・キッズ',
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert(['content' => $category,]);
        }

        $param = [
            'seller_user_id' => 1,
            'item_name' => '腕時計',
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'item_image' => 'watch.jpg',
            'condition' => '良好'
        ];
        DB::table('items')->insert($param);

        $param = [
            'seller_user_id' => 1,
            'item_name' => 'HDD',
            'price' => 5000,
            'description' => '高速で信頼性の高いハードディスク',
            'item_image' => 'HDD.jpg',
            'condition' => '目立った傷や汚れなし'
        ];
        DB::table('items')->insert($param);

        $param = [
            'seller_user_id' => 1,
            'item_name' => '玉ねぎ3束',
            'price' => 300,
            'description' => '新鮮な玉ねぎ3束のセット',
            'item_image' => '玉ねぎ3束.jpg',
            'condition' => 'やや傷や汚れあり'
        ];
        DB::table('items')->insert($param);

        $param = [
            'seller_user_id' => 1,
            'item_name' => '革靴',
            'price' => 4000,
            'description' => 'クラシックなデザインの革靴',
            'item_image' => '革靴.jpg',
            'condition' => '状態が悪い'
        ];
        DB::table('items')->insert($param);

        $param = [
            'seller_user_id' => 1,
            'item_name' => 'ノートPC',
            'price' => 45000,
            'description' => '高性能なノートパソコン',
            'item_image' => 'ノートPC.jpg',
            'condition' => '良好'
        ];
        DB::table('items')->insert($param);

        $param = [
            'seller_user_id' => 1,
            'item_name' => 'マイク',
            'price' => 8000,
            'description' => '高音質のレコーディング用マイク',
            'item_image' => 'マイク.jpg',
            'condition' => '目立った傷や汚れなし'
        ];
        DB::table('items')->insert($param);

        $param = [
            'seller_user_id' => 1,
            'item_name' => 'ショルダーバッグ',
            'price' => 3500,
            'description' => 'おしゃれなショルダーバッグ',
            'item_image' => 'ショルダーバッグ.jpg',
            'condition' => 'やや傷や汚れあり'
        ];
        DB::table('items')->insert($param);

        $param = [
            'seller_user_id' => 1,
            'item_name' => 'タンブラー',
            'price' => 500,
            'description' => '使いやすいタンブラー',
            'item_image' => 'タンブラー.jpg',
            'condition' => '状態が悪い'
        ];
        DB::table('items')->insert($param);

        $param = [
            'seller_user_id' => 1,
            'item_name' => 'コーヒーミル',
            'price' => 4000,
            'description' => '手動のコーヒーミル',
            'item_image' => 'コーヒーミル.jpg',
            'condition' => '良好'
        ];
        DB::table('items')->insert($param);

        $param = [
            'seller_user_id' => 1,
            'item_name' => 'メイクセット',
            'price' => 2500,
            'description' => '便利なメイクアップセット',
            'item_image' => 'メイクセット.jpg',
            'condition' => '目立った傷や汚れなし'
        ];
        DB::table('items')->insert($param);

        $categoryItemRelations = [
            // 商品1に「ファッション、メンズ、アクセサリー」を関連付け
            ['item_id' => 1, 'category_id' => 1],
            ['item_id' => 1, 'category_id' => 5],
            ['item_id' => 1, 'category_id' => 12],
            // 商品2に「家電」を関連付け
            ['item_id' => 2, 'category_id' => 2],
            // 商品3に「キッチン」を関連付け
            ['item_id' => 3, 'category_id' => 10],
            // 商品4に「ファッション」を関連付け
            ['item_id' => 4, 'category_id' => 1],
            // 商品5に「家電」を関連付け
            ['item_id' => 5, 'category_id' => 2],
            // 商品6に「家電」を関連付け
            ['item_id' => 6, 'category_id' => 2],
            // 商品7に「ファッション」を関連付け
            ['item_id' => 7, 'category_id' => 1],
            // 商品8に「キッチン」を関連付け
            ['item_id' => 8, 'category_id' => 10],
            // 商品9に「インテリア、キッチン」を関連付け
            ['item_id' => 9, 'category_id' => 3],
            ['item_id' => 9, 'category_id' => 10],
            // 商品10に「レディース、コスメ」を関連付け
            ['item_id' => 10, 'category_id' => 4],
            ['item_id' => 10, 'category_id' => 6],
        ];

        foreach ($categoryItemRelations as $relation) {
            DB::table('category_item')->insert($relation);
        }
    }
}
