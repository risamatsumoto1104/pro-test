<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'seller_user_id' => 1,
            'item_name' => '腕時計',
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'item_image' => 'images/腕時計.jpg',
            'condition' => '良好'
        ];
        DB::table('items')->insert($param);

        $param = [
            'seller_user_id' => 1,
            'item_name' => 'HDD',
            'price' => 5000,
            'description' => '高速で信頼性の高いハードディスク',
            'item_image' => 'images/HDD.jpg',
            'condition' => '目立った傷や汚れなし'
        ];
        DB::table('items')->insert($param);

        $param = [
            'seller_user_id' => 1,
            'item_name' => '玉ねぎ3束',
            'price' => 300,
            'description' => '新鮮な玉ねぎ3束のセット',
            'item_image' => 'images/玉ねぎ3束.jpg',
            'condition' => 'やや傷や汚れあり'
        ];
        DB::table('items')->insert($param);

        $param = [
            'seller_user_id' => 1,
            'item_name' => '革靴',
            'price' => 4000,
            'description' => 'クラシックなデザインの革靴',
            'item_image' => 'images/革靴.jpg',
            'condition' => '状態が悪い'
        ];
        DB::table('items')->insert($param);

        $param = [
            'seller_user_id' => 1,
            'item_name' => 'ノートPC',
            'price' => 45000,
            'description' => '高性能なノートパソコン',
            'item_image' => 'images/ノートPC.jpg',
            'condition' => '良好'
        ];
        DB::table('items')->insert($param);

        $param = [
            'seller_user_id' => 1,
            'item_name' => 'マイク',
            'price' => 8000,
            'description' => '高音質のレコーディング用マイク',
            'item_image' => 'images/マイク.jpg',
            'condition' => '目立った傷や汚れなし'
        ];
        DB::table('items')->insert($param);

        $param = [
            'seller_user_id' => 1,
            'item_name' => 'ショルダーバッグ',
            'price' => 3500,
            'description' => 'おしゃれなショルダーバッグ',
            'item_image' => 'images/ショルダーバッグ.jpg',
            'condition' => 'やや傷や汚れあり'
        ];
        DB::table('items')->insert($param);

        $param = [
            'seller_user_id' => 1,
            'item_name' => 'タンブラー',
            'price' => 500,
            'description' => '使いやすいタンブラー',
            'item_image' => 'images/タンブラー.jpg',
            'condition' => '状態が悪い'
        ];
        DB::table('items')->insert($param);

        $param = [
            'seller_user_id' => 1,
            'item_name' => 'コーヒーミル',
            'price' => 4000,
            'description' => '手動のコーヒーミル',
            'item_image' => 'images/コーヒーミル.jpg',
            'condition' => '良好'
        ];
        DB::table('items')->insert($param);

        $param = [
            'seller_user_id' => 1,
            'item_name' => 'メイクセット',
            'price' => 2500,
            'description' => '便利なメイクアップセット',
            'item_image' => 'images/メイクセット.jpg',
            'condition' => '目立った傷や汚れなし'
        ];
        DB::table('items')->insert($param);
    }
}
