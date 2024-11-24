<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
