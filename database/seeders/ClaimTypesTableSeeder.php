<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClaimTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('claim_types')->insert([
            ['name'  => 'Reclamo', 'description' => 'Disconformidad relacionada a la calidad de productos y servicios'],
            ['name'  => 'Queja', 'description' => 'Malestar o descontento respecto a la relación con el público'],
        ]);
    }
}
