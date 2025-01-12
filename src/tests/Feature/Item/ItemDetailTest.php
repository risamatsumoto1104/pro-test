<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Tests\Traits\DatabaseSeedTrait;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase, DatabaseSeedTrait;

    // データベースのauto_incrementをリセット
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');

        User::create([
            'name' => 'Test User',
            'email' => 'test1@example.com',
            'password' => bcrypt('password123'),
        ]);

        // トレイトメソッドを使用してシーディングを実行
        $this->seedDatabase();
}
