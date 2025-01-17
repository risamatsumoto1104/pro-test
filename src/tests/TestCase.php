<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    // テスト用のデータベースを初期化
    protected function resetDatabase()
    {
        $this->artisan('config:clear');
        $this->artisan('cache:clear');
        $this->artisan('route:clear');
        $this->artisan('view:clear');

        // 外部キー制約を無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // テーブルのデータをリセット（truncate）
        DB::table('users')->truncate();
        DB::table('categories')->truncate();
        DB::table('items')->truncate();
        DB::table('addresses')->truncate();
        DB::table('category_item')->truncate();
        DB::table('address_item')->truncate();
        DB::table('profiles')->truncate();
        DB::table('likes')->truncate();
        DB::table('comments')->truncate();
        DB::table('purchases')->truncate();

        // 外部キー制約を再度有効化
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // データベースのauto_incrementをリセット
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE items AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE addresses AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE category_item AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE address_item AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE profiles AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE likes AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE comments AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE purchases AUTO_INCREMENT = 1;');
    }
}
