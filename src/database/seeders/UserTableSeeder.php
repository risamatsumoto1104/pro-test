<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'user1',
            'email' => 'user1@example.com',
            'password' => Hash::make('password1'),
            'email_verified_at' => now(),
        ];
        DB::table('users')->insert($param);

        $param = [
            'name' => 'user2',
            'email' => 'user2@example.com',
            'password' => Hash::make('password2'),
            'email_verified_at' => now(),
        ];
        DB::table('users')->insert($param);

        $param = [
            'name' => 'user3',
            'email' => 'user3@example.com',
            'password' => Hash::make('password3'),
            'email_verified_at' => now(),
        ];
        DB::table('users')->insert($param);
    }
}
