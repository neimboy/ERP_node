<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empleado;

class EmpleadoSeeder extends Seeder
{
    public function run(): void
    {
        if (Empleado::count() === 0) {
            Empleado::create([
                'DNI' => '00000000',
                'Nombre' => 'Empleado Demo',
                'Correo' => 'empleado@erp.com',
                'Telefono' => '000000000',
                'Fecha_Ingreso' => now(),
                'Estado' => 1
            ]);
        }
    }
}
