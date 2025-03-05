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
            'user_id' => 1,
            'name' => 'テストユーザー',
            'post_code' => '123-4567',
            'address' => '東京都江戸川区',
            'building' => '江戸川ビル101',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
