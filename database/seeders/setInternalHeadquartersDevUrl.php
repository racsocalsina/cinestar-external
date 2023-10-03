<?php

namespace Database\Seeders;

use App\Models\Headquarters\Headquarter;
use Illuminate\Database\Seeder;

class setInternalHeadquartersDevUrl extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Headquarter::query()->update([
            'api_url'=>env('DEV_INTERNAL_URL', 'https://cinestar-api-internal.pappstest.com')
        ]);
    }
}
