<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contabilidad\CuentaContable;

/**
 * Siembra las cuentas PCGE mínimas que usa IntegracionContableService.
 *
 * Ejecutar con:  php artisan db:seed --class=CuentasContablesSeeder
 *
 * Si ya tienes algunas cuentas, este seeder las omite (firstOrCreate).
 */
class CuentasContablesSeeder extends Seeder
{
    public function run(): void
    {
        $cuentas = [
            // ── Activo ──────────────────────────────────────────
            ['Codigo' => '10', 'Nombre_Cuenta' => 'Efectivo y Equivalentes de Efectivo (Caja y Bancos)', 'Tipo' => 'Activo'],
            ['Codigo' => '12', 'Nombre_Cuenta' => 'Cuentas por Cobrar Comerciales – Terceros',           'Tipo' => 'Activo'],

            // ── Pasivo ───────────────────────────────────────────
            ['Codigo' => '40', 'Nombre_Cuenta' => 'Tributos, Contraprestaciones y Aportes al Sistema (IGV)', 'Tipo' => 'Pasivo'],
            ['Codigo' => '41', 'Nombre_Cuenta' => 'Remuneraciones y Participaciones por Pagar',          'Tipo' => 'Pasivo'],
            ['Codigo' => '42', 'Nombre_Cuenta' => 'Cuentas por Pagar Comerciales – Terceros',            'Tipo' => 'Pasivo'],

            // ── Ingreso ──────────────────────────────────────────
            ['Codigo' => '70', 'Nombre_Cuenta' => 'Ventas',                                              'Tipo' => 'Ingreso'],

            // ── Costo ────────────────────────────────────────────
            ['Codigo' => '60', 'Nombre_Cuenta' => 'Compras',                                             'Tipo' => 'Costo'],

            // ── Gasto ────────────────────────────────────────────
            ['Codigo' => '62', 'Nombre_Cuenta' => 'Gastos de Personal, Directores y Gerentes',          'Tipo' => 'Gasto'],
        ];

        foreach ($cuentas as $cuenta) {
            CuentaContable::firstOrCreate(
                ['Codigo' => $cuenta['Codigo']],
                $cuenta
            );
        }

        $this->command->info('✅ Cuentas PCGE base registradas correctamente.');
    }
}