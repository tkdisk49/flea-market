<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('addresses')->insert([
            [
            'user_id' => 1,
            'name' => '出品ユーザー1',
            'post_code' => '123-4567',
            'address' => '東京都江戸川区',
            'building' => '江戸川ビル101',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'user_id' => 2,
            'name' => '出品ユーザー2',
            'post_code' => '234-5678',
            'address' => '大阪府大阪市',
            'building' => '大阪ビル202',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'user_id' => 3,
            'name' => 'ユーザー',
            'post_code' => '345-6789',
            'address' => '北海道札幌市',
            'building' => '札幌ビル303',
            'created_at' => now(),
            'updated_at' => now(),
            ]
        ]);
    }
}
