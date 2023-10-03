<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->insert([
            [
                'name'  => 'India',
            ],
            [
                'name'  => 'China',
            ],
            [
                'name'  => 'Estados Unidos',
            ],
            [
                'name'  => 'Japón',
            ],
            [
                'name'  => 'México',
            ],
            [
                'name'  => 'España',
            ]
        ]);
    }
}
