<?php

namespace Database\Seeders;

use App\Models\Empleado;
use Illuminate\Database\Seeder;

class EmpleadoSeeder extends Seeder
{
    public function run(): void
    {
        $empleados = [
            [
                'DNI' => '00000001',
                'Nombre' => 'Juan Pérez García',
                'Correo' => 'juan.perez@erp.com',
                'Telefono' => '960111111',
                'Fecha_Ingreso' => '2024-01-15',
                'Estado' => 1
            ],
            [
                'DNI' => '00000002',
                'Nombre' => 'María López Díaz',
                'Correo' => 'maria.lopez@erp.com',
                'Telefono' => '960222222',
                'Fecha_Ingreso' => '2024-02-20',
                'Estado' => 1
            ],
            [
                'DNI' => '00000003',
                'Nombre' => 'Carlos Rodríguez',
                'Correo' => 'carlos.rodriguez@erp.com',
                'Telefono' => '960333333',
                'Fecha_Ingreso' => '2024-03-10',
                'Estado' => 1
            ],
            [
                'DNI' => '00000004',
                'Nombre' => 'Ana Martínez Torres',
                'Correo' => 'ana.martinez@erp.com',
                'Telefono' => '960444444',
                'Fecha_Ingreso' => '2024-04-05',
                'Estado' => 1
            ],
            [
                'DNI' => '00000005',
                'Nombre' => 'Luis Fernández',
                'Correo' => 'luis.fernandez@erp.com',
                'Telefono' => '960555555',
                'Fecha_Ingreso' => '2024-05-12',
                'Estado' => 1
            ],
            [
                'DNI' => '00000006',
                'Nombre' => 'Sofia Herrera',
                'Correo' => 'sofia.herrera@erp.com',
                'Telefono' => '960666666',
                'Fecha_Ingreso' => '2024-06-01',
                'Estado' => 1
            ],
            [
                'DNI' => '00000007',
                'Nombre' => 'Pedro Gonzales',
                'Correo' => 'pedro.gonzales@erp.com',
                'Telefono' => '960777777',
                'Fecha_Ingreso' => '2024-07-18',
                'Estado' => 1
            ],
            [
                'DNI' => '00000008',
                'Nombre' => 'Carmen Quispe',
                'Correo' => 'carmen.quispe@erp.com',
                'Telefono' => '960888888',
                'Fecha_Ingreso' => '2024-08-22',
                'Estado' => 1
            ],
        ];

        foreach ($empleados as $empleado) {
            Empleado::create($empleado);
        }
    }
}