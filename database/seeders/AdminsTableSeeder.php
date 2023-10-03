<?php

namespace Database\Seeders;

use App\Models\Admins\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            [
                'name'             => 'Administrador',
                'lastname'         => 'General',
                'email'            => 'admin@cinestar.pe',
                'document_type_id' => 1,
                'document_number'  => '00000000',
                'headquarter_id'   => 1,
                'entry_date'       => '2020-01-01',
                'password'         => bcrypt('Cine_Star#2020'),
                'status'           => true
            ],
        ]);

        $admin = Admin::where('document_number', '00000000')->first();
        $admin->attachRole('super-admin');
    }
}
