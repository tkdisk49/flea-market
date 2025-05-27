<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
            'id' => 1,
            'name' => '出品ユーザー1',
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'id' => 2,
            'name' => '出品ユーザー2',
            'email' => 'user2@example.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'id' => 3,
            'name' => 'ユーザー',
            'email' => 'user3@example.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
            ],
        ]);
    }
}
