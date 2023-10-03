<?php

namespace Database\Seeders;

use App\Models\Banners\Banner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Banner::query()->delete();
        DB::table('banners')->insert([
            [
                'link'  => 'http://www.cinestar.com.pe/inicio/promociones',
                'path'  => '11.jpg',
                'type'  => 'web',
            ],
            [
                'link'  => 'http://www.cinestar.com.pe/inicio/promociones',
                'path'  => 'PROM_semanal2020-01.jpg',
                'type'  => 'web',
            ],
            [
                'link'  => 'http://www.cinestar.com.pe/inicio/promociones',
                'path'  => 'PROM_UNIVERSITARIA20202.jpg',
                'type'  => 'web',
            ],
            [
                'link'  => 'http://www.cinestar.com.pe/inicio/promociones',
                'path'  => 'PROMO_VIERNESMARZO-06_(1).jpg',
                'type'  => 'web',
            ],
            [
                'link'  => 'http://www.cinestar.com.pe/inicio/promociones',
                'path'  => 'banner1.jpg',
                'type'  => 'movil',
            ],
            [
                'link'  => 'http://www.cinestar.com.pe/inicio/promociones',
                'path'  => 'banner2.jpg',
                'type'  => 'movil',
            ]
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
