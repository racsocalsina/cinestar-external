<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieTimeTariffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('movie_time_tariff')->insert([
            [
                'movie_time_id' => '17',
                'movie_tariff_id' => '1',
                'online_price' => 7,
                'is_presale' => false,
                'remote_id' => '0001'
            ],
            [
                'movie_time_id' => '18',
                'movie_tariff_id' => '2',
                'online_price' => 5,
                'is_presale' => false,
                'remote_id' => '0001'
            ],
            [
                'movie_time_id' => '62',
                'movie_tarrif_id' => '1',
                'online_price' => 7,
                'is_presale' => false,
                'remote_id' => '0001'
            ],
            [
                'movie_time_id' => '63',
                'movie_tarrif_id' => '2',
                'online_price' => 5,
                'is_presale' => false,
                'remote_id' => '0001'
            ],
            [
                'movie_time_id' => '107',
                'movie_tarrif_id' => '1',
                'online_price' => 7,
                'is_presale' => false,
                'remote_id' => '0001'
            ],
            [
                'movie_time_id' => '108',
                'movie_tarrif_id' => '2',
                'online_price' => 5,
                'is_presale' => false,
                'remote_id' => '0001'
            ]
        ]);
    }
}
