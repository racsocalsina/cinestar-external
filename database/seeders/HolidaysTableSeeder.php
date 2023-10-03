<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HolidaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('holidays')->insert([
            ['name' => 'Año Nuevo', 'scheduled_at' => '01-01'],
            ['name' => 'Jueves Santo', 'scheduled_at' => '04-01'],
            ['name' => 'Viernes Santo', 'scheduled_at' => '04-02'],
            ['name' => 'Día del Trabajo', 'scheduled_at' => '05-01'],
            ['name' => 'San Pedro y San Pablo', 'scheduled_at' => '06-29'],
            ['name' => 'Independencia del Perú', 'scheduled_at' => '07-28'],
            ['name' => 'Independencia del Perú', 'scheduled_at' => '07-29'],
            ['name' => 'Santa Rosa de Lima', 'scheduled_at' => '08-30'],
            ['name' => 'Combate Naval de Angamos', 'scheduled_at' => '10-08'],
            ['name' => 'Día de todos los Santos', 'scheduled_at' => '11-01'],
            ['name' => 'Inmaculada Concepción', 'scheduled_at' => '12-08'],
            ['name' => 'Navidad', 'scheduled_at' => '12-25'],
        ]);
    }
}
