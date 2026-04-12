<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = [
            [
                'Documento' => '10000000001',
                'Nombre' => 'Empresa ABC S.A.C.',
                'Correo' => 'contacto@abc.com',
                'Telefono' => '951123456'
            ],
            [
                'Documento' => '10000000002',
                'Nombre' => 'Corporación XYZ Ltda.',
                'Correo' => 'info@xyz.com',
                'Telefono' => '952234567'
            ],
            [
                'Documento' => '10000000003',
                'Nombre' => 'Industrias Delta S.A.',
                'Correo' => 'ventas@delta.com',
                'Telefono' => '953345678'
            ],
            [
                'Documento' => '10000000004',
                'Nombre' => 'Constructora Omega',
                'Correo' => 'proyectos@omega.com',
                'Telefono' => '954456789'
            ],
            [
                'Documento' => '10000000005',
                'Nombre' => 'Servicios Integrales Beta',
                'Correo' => 'admin@beta.com',
                'Telefono' => '955567890'
            ],
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }
    }
}