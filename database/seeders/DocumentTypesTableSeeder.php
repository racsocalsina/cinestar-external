<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('document_types')->insert([
            ['name'  => 'DNI', 'code' => '01'],
            ['name'  => 'PASAPORTE', 'code' => '07'],
            ['name'  => 'CARNET DE EXTRANJERIA', 'code' => '04'],
        ]);
    }
}
