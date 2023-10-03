<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypesPaymentMethodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('types_payment_method')->insert([
            [
                'remote_code'   => '150',
                'name'          => 'PROMOCION ENTEL',
                'type_currency' => 1,
                'payment_type'  => 0,
            ],
        ]);
    }
}
