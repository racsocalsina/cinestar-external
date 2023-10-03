<?php

namespace Database\Seeders;

use App\Models\Permissions\Permission;
use App\Models\Roles\Role;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cinemaAdminRole = Role::where('name', 'cinema-admin')->first();

        $cinemaAdminRole->attachPermissions([
            'read-movie',
            'read-product',
            'read-combo',
            'read-headquarter',
            'update-headquarter'
        ]);
    }
}
