<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => '一般ユーザー',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'profile' => 'これは一般ユーザーです。',
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '管理者ユーザー',
                'email' => 'admin@example.com',
                'password' => Hash::make('adminpass'),
                'profile' => 'これは管理者です。',
                'is_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
