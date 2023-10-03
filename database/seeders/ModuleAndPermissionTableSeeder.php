<?php

namespace Database\Seeders;

use App\Models\Admins\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleAndPermissionTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('modules')->insert([
            ['id' => 1, 'name' => 'user', 'display_name' => 'Usuarios'],
            ['id' => 2, 'name' => 'movie', 'display_name' => 'Peliculas'],
            ['id' => 3, 'name' => 'role', 'display_name' => 'Roles'],
            ['id' => 4, 'name' => 'moviegenre', 'display_name' => 'Géneros de Peliculas'],
            ['id' => 5, 'name' => 'banner', 'display_name' => 'Banners'],
            ['id' => 6, 'name' => 'headquarter', 'display_name' => 'Sedes'],
            ['id' => 7, 'name' => 'country', 'display_name' => 'Países'],
            ['id' => 8, 'name' => 'city', 'display_name' => 'Ciudades'],
            ['id' => 9, 'name' => 'product', 'display_name' => 'Productos'],
            ['id' => 10, 'name' => 'combo', 'display_name' => 'Combos'],
            ['id' => 11, 'name' => 'roomtype', 'display_name' => 'Tipos de salas'],
            ['id' => 12, 'name' => 'promotion', 'display_name' => 'Promociones'],
            ['id' => 13, 'name' => 'contentmanagement', 'display_name' => 'Gestion de Contenido'],
            ['id' => 14, 'name' => 'reports', 'display_name' => 'Reportes'],
        ]);

        DB::table('permissions')->insert([
            // User
            ['name' => 'create-user', 'display_name' => 'Crear Usuario', 'module_id' => 1],
            ['name' => 'read-user', 'display_name' => 'Ver Usuario', 'module_id' => 1],
            ['name' => 'update-user', 'display_name' => 'Actualizar Usuario', 'module_id' => 1],
            ['name' => 'delete-user', 'display_name' => 'Eliminar Usuario', 'module_id' => 1],

            // Movie
            ['name' => 'create-movie', 'display_name' => 'Crear Pelicula', 'module_id' => 2],
            ['name' => 'read-movie', 'display_name' => 'Ver Pelicula', 'module_id' => 2],
            ['name' => 'update-movie', 'display_name' => 'Actualizar Pelicula', 'module_id' => 2],
            ['name' => 'delete-movie', 'display_name' => 'Eliminar Pelicula', 'module_id' => 2],

            // Role
            ['name' => 'create-role', 'display_name' => 'Crear Rol', 'module_id' => 3],
            ['name' => 'read-role', 'display_name' => 'Ver Rol', 'module_id' => 3],
            ['name' => 'update-role', 'display_name' => 'Actualizar Rol', 'module_id' => 3],
            ['name' => 'delete-role', 'display_name' => 'Eliminar Rol', 'module_id' => 3],

            // Movie Genre
            ['name' => 'create-moviegenre', 'display_name' => 'Crear Género Pelicula', 'module_id' => 4],
            ['name' => 'read-moviegenre', 'display_name' => 'Ver Género Pelicula', 'module_id' => 4],
            ['name' => 'update-moviegenre', 'display_name' => 'Actualizar Género Pelicula', 'module_id' => 4],
            ['name' => 'delete-moviegenre', 'display_name' => 'Eliminar Género Pelicula', 'module_id' => 4],

            // Banner
            ['name' => 'create-banner', 'display_name' => 'Crear Banner', 'module_id' => 5],
            ['name' => 'read-banner', 'display_name' => 'Ver Banner', 'module_id' => 5],
            ['name' => 'update-banner', 'display_name' => 'Actualizar Banner', 'module_id' => 5],
            ['name' => 'delete-banner', 'display_name' => 'Eliminar Banner', 'module_id' => 5],

            // Headquarter
            ['name' => 'create-headquarter', 'display_name' => 'Crear Sede', 'module_id' => 6],
            ['name' => 'read-headquarter', 'display_name' => 'Ver Sede', 'module_id' => 6],
            ['name' => 'update-headquarter', 'display_name' => 'Actualizar Sede', 'module_id' => 6],
            ['name' => 'delete-headquarter', 'display_name' => 'Eliminar Sede', 'module_id' => 6],

            // Country
            ['name' => 'create-country', 'display_name' => 'Crear Pais', 'module_id' => 7],
            ['name' => 'read-country', 'display_name' => 'Ver Pais', 'module_id' => 7],
            ['name' => 'update-country', 'display_name' => 'Actualizar Pais', 'module_id' => 7],
            ['name' => 'delete-country', 'display_name' => 'Eliminar Pais', 'module_id' => 7],

            // City
            ['name' => 'create-city', 'display_name' => 'Crear Ciudad', 'module_id' => 8],
            ['name' => 'read-city', 'display_name' => 'Ver Ciudad', 'module_id' => 8],
            ['name' => 'update-city', 'display_name' => 'Actualizar Ciudad', 'module_id' => 8],
            ['name' => 'delete-city', 'display_name' => 'Eliminar Ciudad', 'module_id' => 8],

            // Product
            ['name' => 'create-product', 'display_name' => 'Crear Producto', 'module_id' => 9],
            ['name' => 'read-product', 'display_name' => 'Ver Producto', 'module_id' => 9],
            ['name' => 'update-product', 'display_name' => 'Actualizar Producto', 'module_id' => 9],
            ['name' => 'delete-product', 'display_name' => 'Eliminar Producto', 'module_id' => 9],

            // Combo
            ['name' => 'create-combo', 'display_name' => 'Crear Combo', 'module_id' => 10],
            ['name' => 'read-combo', 'display_name' => 'Ver Combo', 'module_id' => 10],
            ['name' => 'update-combo', 'display_name' => 'Actualizar Combo', 'module_id' => 10],
            ['name' => 'delete-combo', 'display_name' => 'Eliminar Combo', 'module_id' => 10],

            // RoomType
            ['name' => 'create-roomtype', 'display_name' => 'Crear Tipo de Sala', 'module_id' => 11],
            ['name' => 'read-roomtype', 'display_name' => 'Ver Tipo de Sala', 'module_id' => 11],
            ['name' => 'update-roomtype', 'display_name' => 'Actualizar Tipo de Sala', 'module_id' => 11],
            ['name' => 'delete-roomtype', 'display_name' => 'Eliminar Tipo de Sala', 'module_id' => 11],

            // Promotion
            ['name' => 'create-promotion', 'display_name' => 'Crear Promoción', 'module_id' => 12],
            ['name' => 'read-promotion', 'display_name' => 'Ver Promoción', 'module_id' => 12],
            ['name' => 'update-promotion', 'display_name' => 'Actualizar Promoción', 'module_id' => 12],
            ['name' => 'delete-promotion', 'display_name' => 'Eliminar Promoción', 'module_id' => 12],

            // Content-Management
            ['name' => 'update-contentmanagement', 'display_name' => 'Actualizar Gestion de Contenido', 'module_id' => 13],

            // Reports
            ['name' => 'read-reports', 'display_name' => 'Reportes', 'module_id' => 14],
        ]);
    }
}
