<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieTariffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('movie_tariffs')->insert([
            [
                'name' => 'TARIFA PLANA',
                'remote_funtar' => 'Z'
            ],
        ]);
    }
}
