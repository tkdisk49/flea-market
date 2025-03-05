<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $images = [
            'watch.jpg',
            'hdd.jpg',
            'onions.jpg',
            'shoes.jpg',
            'laptop.jpg',
            'mic.jpg',
            'bag.jpg',
            'tumbler.jpg',
            'coffee.jpg',
            'make.jpg',
        ];

        foreach ($images as $image) {
            Storage::disk('public')->put(
                "items/{$image}",
                file_get_contents(database_path("seeders/images/{$image}"))
            );
        }

        DB::table('items')->insert([
            [
                'user_id' => 1,
                'name' => '腕時計',
                'price' => 15000,
                'image' => 'items/watch.jpg',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'condition' => 1,
                'brand' => 'Alpha',
            ],
            [
                'user_id' => 1,
                'name' => 'HDD',
                'price' => 5000,
                'image' => 'items/hdd.jpg',
                'description' => '高速で信頼性の高いハードディスク',
                'condition' => 2,
                'brand' => 'Bravo',
            ],
            [
                'user_id' => 1,
                'name' => '玉ねぎ3束',
                'price' => 300,
                'image' => 'items/onions.jpg',
                'description' => '新鮮な玉ねぎ3束のセット',
                'condition' => 3,
                'brand' => 'Charlie',
            ],
            [
                'user_id' => 1,
                'name' => '革靴',
                'price' => 4000,
                'image' => 'items/shoes.jpg',
                'description' => 'クラシックなデザインの革靴',
                'condition' => 4,
                'brand' => 'Delta',
            ],
            [
                'user_id' => 1,
                'name' => 'ノートPC',
                'price' => 45000,
                'image' => 'items/laptop.jpg',
                'description' => '高性能なノートパソコン',
                'condition' => 1,
                'brand' => 'Alpha',
            ],
            [
                'user_id' => 1,
                'name' => 'マイク',
                'price' => 8000,
                'image' => 'items/mic.jpg',
                'description' => '高音質のレコーディング用マイク',
                'condition' => 2,
                'brand' => 'Bravo',
            ],
            [
                'user_id' => 1,
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'image' => 'items/bag.jpg',
                'description' => 'おしゃれなショルダーバッグ',
                'condition' => 3,
                'brand' => 'Charlie',
            ],
            [
                'user_id' => 1,
                'name' => 'タンブラー',
                'price' => 500,
                'image' => 'items/tumbler.jpg',
                'description' => '使いやすいタンブラー',
                'condition' => 4,
                'brand' => 'Delta',
            ],
            [
                'user_id' => 1,
                'name' => 'コーヒーミル',
                'price' => 4000,
                'image' => 'items/coffee.jpg',
                'description' => '手動のコーヒーミル',
                'condition' => 1,
                'brand' => 'Alpha',
            ],
            [
                'user_id' => 1,
                'name' => 'メイクセット',
                'price' => 2500,
                'image' => 'items/make.jpg',
                'description' => '便利なメイクアップセット',
                'condition' => 2,
                'brand' => 'Bravo',
            ],
        ]);
    }
}
