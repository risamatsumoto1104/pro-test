<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\Artisan;

trait DatabaseSeedTrait
{
    // ダミーデータを作成する共通メソッド
    public function seedDatabase()
    {
        // シーダーの実行
        Artisan::call('db:seed', ['--class' => 'CategoriesTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ItemsTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'CategoryItemTableSeeder']);
    }
}
