<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cities')->insert([
            [
                'name'  => 'Lima',
            ],
            [
                'name'  => 'Iquitos',
            ],
            [
                'name'  => 'Ica',
            ],
        ]);
    }
}
