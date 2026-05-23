<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        if (Cliente::count() === 0) {
            Cliente::create([
                'Documento' => '00000001',
                'Nombre' => 'Cliente Demo',
                'Correo' => 'cliente@erp.com',
                'Telefono' => '000000000'
            ]);
        }
    }
}
