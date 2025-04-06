<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::where('email', 'user1@example.com')->first();  // ユーザー1を取得
        $user2 = User::where('email', 'user2@example.com')->first();  // ユーザー2を取得
        $user3 = User::where('email', 'user3@example.com')->first();  // ユーザー3を取得

        $param = [
            'user_id' => $user1->user_id,
            'postal_code' => '111-1111',
            'address' => '福岡県福岡市博多区1-1-1',
            'building' => '博多ビル'
        ];
        DB::table('addresses')->insert($param);

        $param = [
            'user_id' => $user2->user_id,
            'postal_code' => '222-2222',
            'address' => '福岡県福岡市博多区2-2',
            'building' => 'コーポ博多'
        ];
        DB::table('addresses')->insert($param);

        $param = [
            'user_id' => $user3->user_id,
            'postal_code' => '333-3333',
            'address' => '福岡県福岡市博多区3-3-3',
            'building' => '博多マンション'
        ];
        DB::table('addresses')->insert($param);
    }
}
