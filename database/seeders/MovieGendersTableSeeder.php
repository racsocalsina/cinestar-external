<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieGendersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('movie_genders')->insert([
            [
                'name'  => 'Acción',
                'short' => 'Acción',
            ],
            [
                'name'  => 'Ciencia ficción',
                'short' => 'Ciencia ficción',
            ],
            [
                'name'  => 'Comedia',
                'short' => 'Comedia',
            ],
            [
                'name'  => 'Drama',
                'short' => 'Drama',
            ],
            [
                'name'  => 'Terror',
                'short' => 'Terror',
            ],
            [
                'name'  => 'Documental',
                'short' => 'Documental',
            ]
        ]);
    }
}
