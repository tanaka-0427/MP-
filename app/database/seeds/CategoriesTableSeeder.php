<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            ['name' => '家電'],
            ['name' => '本・マンガ'],
            ['name' => 'ファッション'],
            ['name' => 'ゲーム'],
            ['name' => 'その他'],
        ]);
    }
}
