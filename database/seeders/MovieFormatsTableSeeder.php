<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieFormatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('movie_formats')->insert([
            [
                'name'  => 'Película 2D',
                'short' => '2D',
            ],
            [
                'name'  => 'Película 3D',
                'short' => '3D',
            ],
            [
                'name'  => 'Película 4X',
                'short' => '4X',
            ],
        ]);
    }
}
