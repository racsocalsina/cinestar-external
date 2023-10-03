<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rooms')->insert([
            // headquarter = 1
            [
                'headquarter_id' => 1,
                'room_type_id'   => 1,
                'remote_salkey'  => '00001',
                'room_number'    => '01',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 01'
            ],
            [
                'headquarter_id' => 1,
                'room_type_id'   => 1,
                'remote_salkey'  => '00001',
                'room_number'    => '02',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 02'
            ],
            [
                'headquarter_id' => 1,
                'room_type_id'   => 2,
                'remote_salkey'  => '00001',
                'room_number'    => '03',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 03'
            ],
            [
                'headquarter_id' => 1,
                'room_type_id'   => 3,
                'remote_salkey'  => '00001',
                'room_number'    => '04',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 04'
            ],
            // headquarter = 2
            [
                'headquarter_id' => 2,
                'room_type_id'   => 1,
                'remote_salkey'  => '00001',
                'room_number'    => '01',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 01'
            ],
            [
                'headquarter_id' => 2,
                'room_type_id'   => 1,
                'remote_salkey'  => '00001',
                'room_number'    => '02',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 02'
            ],
            [
                'headquarter_id' => 2,
                'room_type_id'   => 2,
                'remote_salkey'  => '00001',
                'room_number'    => '03',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 03'
            ],
            [
                'headquarter_id' => 2,
                'room_type_id'   => 3,
                'remote_salkey'  => '00001',
                'room_number'    => '04',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 04'
            ],
            // headquarter = 3
            [
                'headquarter_id' => 3,
                'room_type_id'   => 1,
                'remote_salkey'  => '00001',
                'room_number'    => '01',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 01'
            ],
            [
                'headquarter_id' => 3,
                'room_type_id'   => 1,
                'remote_salkey'  => '00001',
                'room_number'    => '02',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 02'
            ],
            [
                'headquarter_id' => 3,
                'room_type_id'   => 2,
                'remote_salkey'  => '00001',
                'room_number'    => '03',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 03'
            ],
            [
                'headquarter_id' => 3,
                'room_type_id'   => 3,
                'remote_salkey'  => '00001',
                'room_number'    => '04',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 04'
            ],
            // headquarter = 4
            [
                'headquarter_id' => 4,
                'room_type_id'   => 1,
                'remote_salkey'  => '00001',
                'room_number'    => '01',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 01'
            ],
            [
                'headquarter_id' => 4,
                'room_type_id'   => 1,
                'remote_salkey'  => '00001',
                'room_number'    => '02',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 02'
            ],
            [
                'headquarter_id' => 4,
                'room_type_id'   => 2,
                'remote_salkey'  => '00001',
                'room_number'    => '03',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 03'
            ],
            [
                'headquarter_id' => 4,
                'room_type_id'   => 3,
                'remote_salkey'  => '00001',
                'room_number'    => '04',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 04'
            ],
            // headquarter = 5
            [
                'headquarter_id' => 5,
                'room_type_id'   => 1,
                'remote_salkey'  => '00001',
                'room_number'    => '01',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 01'
            ],
            [
                'headquarter_id' => 5,
                'room_type_id'   => 1,
                'remote_salkey'  => '00001',
                'room_number'    => '02',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 02'
            ],
            [
                'headquarter_id' => 5,
                'room_type_id'   => 2,
                'remote_salkey'  => '00001',
                'room_number'    => '03',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 03'
            ],
            [
                'headquarter_id' => 5,
                'room_type_id'   => 3,
                'remote_salkey'  => '00001',
                'room_number'    => '04',
                'capacity'       => 200,
                'is_numerate'    => false,
                'number_rows'    => 20,
                'number_columns' => 10,
                'name'           => 'Benavides 04'
            ],
        ]);
    }
}
