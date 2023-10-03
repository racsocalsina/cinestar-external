<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('room_types')->insert([
            [
                'name'      => '2D',
            ],
            [
                'name'      => '3D',
            ],
            [
                'name'      => '4X',
            ],
        ]);
    }
}
