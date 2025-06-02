<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermisosSeeder extends Seeder
{
    public function run()
    {
        // Roles base
        $superAdmin = Role::create(['name' => 'super-admin']);
        $adminEmpresa = Role::create(['name' => 'admin-empresa']);
        $adminSubempresa = Role::create(['name' => 'admin-subempresa']);
        $adminSucursal = Role::create(['name' => 'admin-sucursal']);
        $empleadoBodega = Role::create(['name' => 'empleado-bodega']);

        // Permisos básicos
        $permisos = [
            'crear-empresa',
            'editar-empresa',
            'eliminar-empresa',
            'ver-empresa',
            'crear-subempresa',
            'editar-subempresa',
            'ver-subempresa',
            'eliminar-subempresa',
            'crear-sucursal',
            'editar-sucursal',
            'ver-sucursal',
            'eliminar-sucursal',
            'invitar-usuario',
            'ver-usuarios',
            'editar-usuarios',
            'eliminar-usuarios',
            // Permisos específicos de bodega, revisiones, etc.
            'ver-bodega',
            'crear-revision',
            // ...
        ];

        foreach ($permisos as $permiso) {
            Permission::create(['name' => $permiso]);
        }

        // Asignar permisos a roles según jerarquía
        $superAdmin->givePermissionTo(Permission::all());

        $adminEmpresa->givePermissionTo([
            'crear-subempresa','editar-subempresa','ver-subempresa','eliminar-subempresa',
            'crear-sucursal','editar-sucursal','ver-sucursal','eliminar-sucursal',
            'invitar-usuario','ver-usuarios','editar-usuarios',
        ]);

        $adminSubempresa->givePermissionTo([
            'crear-sucursal','editar-sucursal','ver-sucursal','eliminar-sucursal',
            'invitar-usuario','ver-usuarios','editar-usuarios',
        ]);

        $adminSucursal->givePermissionTo([
            'ver-sucursal','invitar-usuario','ver-usuarios',
        ]);

        $empleadoBodega->givePermissionTo(['ver-bodega','crear-revision']);
    }
}
