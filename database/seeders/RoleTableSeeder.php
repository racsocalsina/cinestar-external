<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name'  => 'super-admin', 'display_name'  => 'Super Admin'],
            ['name'  => 'cinema-admin', 'display_name'  => 'Administrador de Cine'],
            ['name'  => 'mkt', 'display_name'  => 'Marketing'],
        ]);
    }
}
