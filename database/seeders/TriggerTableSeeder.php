<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TriggerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = app_path('../database/scripts/QmaesocTrigger.sql');

        $contents = file_get_contents($path);
        DB::connection('cinestar_socios')->unprepared($contents);
    }
}
