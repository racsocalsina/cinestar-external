<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HeadquarterImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('headquarter_images')->insert([
            [
                'path' => 'fachada_benavides1.jpg',
                'headquarter_id' => 1,
                'is_main_image' => true
            ],
            [
                'path' => 'fachada_ok_aviacion4.jpg',
                'headquarter_id' => 2,
                'is_main_image' => true
            ],
            [
                'path' => 'WhatsApp_Image_2018-12-27_at_5_53_04_PM1.jpg',
                'headquarter_id' => 3,
                'is_main_image' => true
            ],
            [
                'path' => 'fachada_sur1.jpg',
                'headquarter_id' => 4,
                'is_main_image' => true
            ],
            [
                'path' => 'metro_uni_fachada1.jpg',
                'headquarter_id' => 5,
                'is_main_image' => true
            ]
        ]);
    }
}
