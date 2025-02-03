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
        Storage::disk('public')->put(
            'items/watch.jpg',
            file_get_contents(database_path('seeders/images/watch.jpg'))
        );

        Storage::disk('public')->put(
            'items/hdd.jpg',
            file_get_contents(database_path('seeders/images/hdd.jpg'))
        );

        Storage::disk('public')->put(
            'items/onions.jpg',
            file_get_contents(database_path('seeders/images/onions.jpg'))
        );

        Storage::disk('public')->put(
            'items/shoes.jpg',
            file_get_contents(database_path('seeders/images/shoes.jpg'))
        );

        Storage::disk('public')->put(
            'items/laptop.jpg',
            file_get_contents(database_path('seeders/images/laptop.jpg'))
        );

        Storage::disk('public')->put(
            'items/mic.jpg',
            file_get_contents(database_path('seeders/images/mic.jpg'))
        );

        Storage::disk('public')->put(
            'items/bag.jpg',
            file_get_contents(database_path('seeders/images/bag.jpg'))
        );

        Storage::disk('public')->put(
            'items/tumbler.jpg',
            file_get_contents(database_path('seeders/images/tumbler.jpg'))
        );

        Storage::disk('public')->put(
            'items/coffee.jpg',
            file_get_contents(database_path('seeders/images/coffee.jpg'))
        );

        Storage::disk('public')->put(
            'items/make.jpg',
            file_get_contents(database_path('seeders/images/make.jpg'))
        );

        DB::table('items')->insert([
            [
                'user_id' => 1,
                'name' => '腕時計',
                'price' => 15000,
                'image' => 'storage/items/watch.jpg',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'condition' => 1,
                'brand' => 'Alpha',
            ],
            [
                'user_id' => 1,
                'name' => 'HDD',
                'price' => 5000,
                'image' => 'storage/items/hdd.jpg',
                'description' => '高速で信頼性の高いハードディスク',
                'condition' => 2,
                'brand' => 'Bravo',
            ],
            [
                'user_id' => 1,
                'name' => '玉ねぎ3束',
                'price' => 300,
                'image' => 'storage/items/onions.jpg',
                'description' => '新鮮な玉ねぎ3束のセット',
                'condition' => 3,
                'brand' => 'Charlie',
            ],
            [
                'user_id' => 1,
                'name' => '革靴',
                'price' => 4000,
                'image' => 'storage/items/shoes.jpg',
                'description' => 'クラシックなデザインの革靴',
                'condition' => 4,
                'brand' => 'Delta',
            ],
            [
                'user_id' => 1,
                'name' => 'ノートPC',
                'price' => 45000,
                'image' => 'storage/items/laptop.jpg',
                'description' => '高性能なノートパソコン',
                'condition' => 1,
                'brand' => 'Alpha',
            ],
            [
                'user_id' => 1,
                'name' => 'マイク',
                'price' => 8000,
                'image' => 'storage/items/mic.jpg',
                'description' => '高音質のレコーディング用マイク',
                'condition' => 2,
                'brand' => 'Bravo',
            ],
            [
                'user_id' => 1,
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'image' => 'storage/items/bag.jpg',
                'description' => 'おしゃれなショルダーバッグ',
                'condition' => 3,
                'brand' => 'Charlie',
            ],
            [
                'user_id' => 1,
                'name' => 'タンブラー',
                'price' => 500,
                'image' => 'storage/items/tumbler.jpg',
                'description' => '使いやすいタンブラー',
                'condition' => 4,
                'brand' => 'Delta',
            ],
            [
                'user_id' => 1,
                'name' => 'コーヒーミル',
                'price' => 4000,
                'image' => 'storage/items/coffee.jpg',
                'description' => '手動のコーヒーミル',
                'condition' => 1,
                'brand' => 'Alpha',
            ],
            [
                'user_id' => 1,
                'name' => 'メイクセット',
                'price' => 2500,
                'image' => 'storage/items/make.jpg',
                'description' => '便利なメイクアップセット',
                'condition' => 2,
                'brand' => 'Bravo',
            ],
        ]);
    }
}
