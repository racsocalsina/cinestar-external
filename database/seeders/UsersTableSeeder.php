<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'username'      => '74460708',
                'password'      => bcrypt('admin'),
                'resetPassword' => 0,
                'type_user'     => 'client'
            ],
        ]);
    }
}
