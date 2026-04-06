<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ==========================
        // PERMISOS
        // ==========================
        $modules = [
            'contabilidad' => ['view', 'create', 'edit', 'delete', 'export'],
            'ventas' => ['view', 'create', 'edit', 'delete', 'export'],
            'inventario' => ['view', 'create', 'edit', 'delete'],
            'rrhh' => ['view', 'create', 'edit', 'delete'],
            'produccion' => ['view', 'create', 'edit', 'delete'],
            'usuarios' => ['view', 'create', 'edit', 'delete', 'assign_roles'],
        ];

        foreach ($modules as $module => $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'name' => "{$permission}_{$module}",
                    'guard_name' => 'web'
                ]);
            }
        }

        // ==========================
        // ROLES
        // ==========================
        $roles = [
            'Super Admin',
            'Contador',
            'Ventas',
            'Almacenero',
            'RRHH',
            'Produccion',
            'Invitado'
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);
        }

        // ==========================
        // ASIGNACIÓN DE PERMISOS
        // ==========================

        $superAdmin = Role::findByName('Super Admin');
        $superAdmin->syncPermissions(Permission::all());

        Role::findByName('Contador')->syncPermissions([
            'view_contabilidad', 'create_contabilidad', 'edit_contabilidad', 'export_contabilidad'
        ]);

        Role::findByName('Ventas')->syncPermissions([
            'view_ventas', 'create_ventas', 'edit_ventas', 'export_ventas'
        ]);

        Role::findByName('Almacenero')->syncPermissions([
            'view_inventario', 'create_inventario', 'edit_inventario'
        ]);

        Role::findByName('RRHH')->syncPermissions([
            'view_rrhh', 'create_rrhh', 'edit_rrhh'
        ]);

        Role::findByName('Produccion')->syncPermissions([
            'view_produccion', 'create_produccion', 'edit_produccion'
        ]);

        // ==========================
        // USUARIOS
        // ==========================

        $admin = User::firstOrCreate(
            ['email' => 'admin@erp.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('12345678'),
            ]
        );
        $admin->assignRole('Super Admin');

        $contadorUser = User::firstOrCreate(
            ['email' => 'contador@erp.com'],
            [
                'name' => 'Contador',
                'password' => Hash::make('12345678'),
            ]
        );
        $contadorUser->assignRole('Contador');

        $ventasUser = User::firstOrCreate(
            ['email' => 'ventas@erp.com'],
            [
                'name' => 'Ventas',
                'password' => Hash::make('12345678'),
            ]
        );
        $ventasUser->assignRole('Ventas');

        $almaceneroUser = User::firstOrCreate(
            ['email' => 'almacen@erp.com'],
            [
                'name' => 'Almacenero',
                'password' => Hash::make('12345678'),
            ]
        );
        $almaceneroUser->assignRole('Almacenero');

        // ==========================
        // MENSAJES
        // ==========================
        $this->command->info('✅ Roles y permisos creados correctamente');
        $this->command->info('👤 Usuarios listos:');
        $this->command->info('Admin: admin@erp.com / 12345678');
        $this->command->info('Contador: contador@erp.com / 12345678');
        $this->command->info('Ventas: ventas@erp.com / 12345678');
        $this->command->info('Almacenero: almacen@erp.com / 12345678');
    }
}