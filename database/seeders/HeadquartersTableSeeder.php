<?php

namespace Database\Seeders;

use App\Enums\BusinessName;
use App\Enums\TradeName;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HeadquartersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('headquarters')->insert([
            [
                'name' => 'CINESTAR BENAVIDES',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                'address' => 'Avenida Alfredo Benavides Nº 4981 Santiago de Surco',
                'latitude' => '-12.128208',
                'longitude' => '-76.985397',
                'city_id' => 1,
                'point_sale' => '1',
                'api_url' => '',
                'user' => '',
                'password' => '',
                'status' => 1,
                'business_name' => BusinessName::TOP_RANK,
                'trade_name' => TradeName::CINESTAR,
            ],
            [
                'name' => 'CINESTAR AVIACION',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                'address' => 'Calle Tiziano 100 Alt. Cuadra 24 de la Av. Aviación',
                'latitude' => '-12.088989',
                'longitude' => '-77.003190',
                'city_id' => 1,
                'point_sale' => 'B',
                'api_url' => '',
                'user' => '',
                'password' => '',
                'status' => 1,
                'business_name' => BusinessName::TOP_RANK,
                'trade_name' => TradeName::CINESTAR,
            ],
            [
                'name' => 'CINESTAR CHORRILLOS PREMIUM',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                'address' => 'Av. Guardia Civil con Av. Guardia Peruana - Chorrillos',
                'latitude' => '-12.184593',
                'longitude' => '-76.999785',
                'city_id' => 1,
                'point_sale' => 'Q',
                'api_url' => '',
                'user' => '',
                'password' => '',
                'status' => 1,
                'business_name' => BusinessName::TOP_RANK,
                'trade_name' => TradeName::CINESTAR,
            ],
            [
                'name' => 'CINESTAR SUR',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                'address' => 'Avenida Los Héroes 240 – San Juan de Miraflores',
                'latitude' => '-12.152376',
                'longitude' => '-76.976819',
                'city_id' => 1,
                'point_sale' => 'C',
                'api_url' => '',
                'user' => '',
                'password' => '',
                'status' => 1,
                'business_name' => BusinessName::TOP_RANK,
                'trade_name' => TradeName::CINESTAR,
            ],
            [
                'name' => 'CINESTAR METRO UNI',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                'address' => 'Av. Gerardo Unger s/n cuadra 16 – Rímac (Altura de Metro)',
                'latitude' => '-12.012391',
                'longitude' => '-77.051865',
                'city_id' => 1,
                'point_sale' => '7',
                'api_url' => '',
                'user' => '',
                'password' => '',
                'status' => 1,
                'business_name' => BusinessName::TOP_RANK,
                'trade_name' => TradeName::CINESTAR,
            ]
        ]);
    }
}
