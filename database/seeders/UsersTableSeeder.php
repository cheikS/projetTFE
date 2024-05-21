<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
                'login' => 'user1',
                'email' => 'user1@example.com',
                'password' => Hash::make('password1'),
                'role' => 'admin'
            ],
            [
                'login' => 'user2',
                'email' => 'user2@example.com',
                'password' => Hash::make('password2'),
                'role' => 'user'
            ],
            [
                'login' => 'user3',
                'email' => 'user3@example.com',
                'password' => Hash::make('password3'),
                'role' => 'user'
            ],
            [
                'login' => 'user4',
                'email' => 'user4@example.com',
                'password' => Hash::make('password4'),
                'role' => 'user'
            ],
            [
                'login' => 'user5',
                'email' => 'user5@example.com',
                'password' => Hash::make('password5'),
                'role' => 'user'
            ],
        ]);
    }
}
