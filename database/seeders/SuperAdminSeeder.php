<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Usar firstOrCreate con 'email' como criterio de búsqueda.
        $user = User::firstOrCreate(
            ['email' => 'rbarrientos@tikinet.cl'],
            [
                'nombre'           => 'Rodrigo',
                'apellido'         => 'Barrientos',
                'password'         => Hash::make('Hola2025!'),
                'direccion'        => 'Av. Libertador Bernardo OHiggins 1234',
                'cargo'            => 'super-admin',
                'rut'              => '11111111-1',
                'numero_telefono'  => '999999999',
            ]
        );

        // Busca el rol "super-admin" (debe existir previamente)
        $role = Role::where('name', 'super-admin')->first();

        // Asigna el rol solo si existe y no está ya asignado
        if ($role && ! $user->hasRole($role->name)) {
            $user->assignRole($role);
        }
    }
}
