<?php


namespace Database\Seeders;


use App\Enums\ContentManagementCodeKey;
use App\Enums\TradeName;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentManagementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('content_managements')->insert([
            ['key_code'  => ContentManagementCodeKey::PARTNER, 'trade_name' => TradeName::CINESTAR, 'value' => '{ "title": null, "sub_title": null, "description": null, "image": null, "terms": null, "benefits": [] }'],
            ['key_code'  => ContentManagementCodeKey::PARTNER, 'trade_name' => TradeName::MOVIETIME, 'value' => '{ "title": null, "sub_title": null, "description": null, "image": null, "terms": null, "benefits": [] }'],

            ['key_code'  => ContentManagementCodeKey::CORPORATE, 'trade_name' => TradeName::CINESTAR, 'value' => '{ "title": null, "description": null, "email": null, "image": null, "services": [] }'],
            ['key_code'  => ContentManagementCodeKey::CORPORATE, 'trade_name' => TradeName::MOVIETIME, 'value' => '{ "title": null, "description": null, "email": null, "image": null, "services": [] }'],

            ['key_code'  => ContentManagementCodeKey::ABOUT, 'trade_name' => TradeName::CINESTAR, 'value' => '{ "title": null, "items": [] }'],
            ['key_code'  => ContentManagementCodeKey::ABOUT, 'trade_name' => TradeName::MOVIETIME, 'value' => '{ "title": null, "items": [] }'],

            ['key_code'  => ContentManagementCodeKey::TERMS, 'trade_name' => TradeName::CINESTAR, 'value' => '{ "terms": null }'],
            ['key_code'  => ContentManagementCodeKey::TERMS, 'trade_name' => TradeName::MOVIETIME, 'value' => '{ "terms": null }'],

            ['key_code'  => ContentManagementCodeKey::POPUP_BANNER, 'trade_name' => TradeName::CINESTAR, 'value' => '{ "image": null }'],
            ['key_code'  => ContentManagementCodeKey::POPUP_BANNER, 'trade_name' => TradeName::MOVIETIME, 'value' => '{ "image": null }'],
        ]);
    }
}
