<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        $this->call([
            UsersTableSeeder::class,
            CustomersTableSeeder::class,
            MovieGendersTableSeeder::class,
            CountriesTableSeeder::class,
            ClaimIdentificationTypesTableSeeder::class,
            ClaimTypesTableSeeder::class,
            DocumentTypesTableSeeder::class,
            MoviesTableSeeder::class,
            CitiesTableSeeder::class,
            HeadquartersTableSeeder::class,
            HeadquarterImagesTableSeeder::class,
            //RoomsTableSeeder::class,
//            MovieTimesTableSeeder::class,
            RoleTableSeeder::class,
            AdminsTableSeeder::class,
            ModuleAndPermissionTableSeeder::class,
            BannersTableSeeder::class,
            SettingsTableSeeder::class,
            MovieTariffTableSeeder::class,
//            MovieTimeTariffTableSeeder::class,
            TypesPaymentMethodTableSeeder::class,
            PermissionRoleSeeder::class,
            TriggerTableSeeder::class,
            HolidaysTableSeeder::class,
            ContentManagementTableSeeder::class
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
