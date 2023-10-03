<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('customers')->insert([
            [
                'name'            => 'juan jose',
                'lastname'        => 'aranibar gutierrez',
                'email'           => 'alonom42@hotmail.com',
                'document_type'   => config('constants.type_document_dni'),
                'document_number' => '74460708',
                'cellphone'       => '954565234',
                'birthdate'       => '1974-01-06',
                'user_id'         => 1
            ],
        ]);
    }
}
