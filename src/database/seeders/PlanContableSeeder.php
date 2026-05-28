<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder: Plan de Cuentas PCGE
 * Basado en los datos reales de Corporación Andina S.A.C. (Ene–Jun 2025)
 * Ejecutar: php artisan db:seed --class=PlanContableSeeder
 */
class PlanContableSeeder extends Seeder
{
    public function run(): void
    {
        $cuentas = [
            // ── ACTIVO ────────────────────────────────────────────
            ['Codigo' => '10', 'Nombre_Cuenta' => 'Caja y Bancos',                     'Tipo' => 'Activo'],
            ['Codigo' => '12', 'Nombre_Cuenta' => 'Cuentas por Cobrar Comerciales',    'Tipo' => 'Activo'],
            ['Codigo' => '20', 'Nombre_Cuenta' => 'Mercaderías',                        'Tipo' => 'Activo'],
            ['Codigo' => '33', 'Nombre_Cuenta' => 'Inmuebles, Maquinaria y Equipo',    'Tipo' => 'Activo'],
            ['Codigo' => '34', 'Nombre_Cuenta' => 'Intangibles',                        'Tipo' => 'Activo'],

            // ── ACTIVO (CONTRA) ────────────────────────────────────
            ['Codigo' => '39', 'Nombre_Cuenta' => 'Depreciación y Amortización Acumulada', 'Tipo' => 'Activo (Contra)'],

            // ── PASIVO ────────────────────────────────────────────
            ['Codigo' => '40', 'Nombre_Cuenta' => 'Tributos por Pagar (IGV / IR)',     'Tipo' => 'Pasivo'],
            ['Codigo' => '41', 'Nombre_Cuenta' => 'Remuneraciones por Pagar',          'Tipo' => 'Pasivo'],
            ['Codigo' => '42', 'Nombre_Cuenta' => 'Cuentas por Pagar Comerciales',     'Tipo' => 'Pasivo'],
            ['Codigo' => '45', 'Nombre_Cuenta' => 'Obligaciones Financieras (Bancos)', 'Tipo' => 'Pasivo'],

            // ── PATRIMONIO ────────────────────────────────────────
            ['Codigo' => '50', 'Nombre_Cuenta' => 'Capital Social',                    'Tipo' => 'Patrimonio'],
            ['Codigo' => '59', 'Nombre_Cuenta' => 'Resultados Acumulados',             'Tipo' => 'Patrimonio'],
            ['Codigo' => '58', 'Nombre_Cuenta' => 'Reservas',                          'Tipo' => 'Patrimonio'],

            // ── INGRESOS ──────────────────────────────────────────
            ['Codigo' => '70', 'Nombre_Cuenta' => 'Ventas de Mercaderías',             'Tipo' => 'Ingreso'],
            ['Codigo' => '72', 'Nombre_Cuenta' => 'Ingresos por Servicios',            'Tipo' => 'Ingreso'],
            ['Codigo' => '77', 'Nombre_Cuenta' => 'Ingresos Financieros',              'Tipo' => 'Ingreso'],

            // ── COSTO ─────────────────────────────────────────────
            ['Codigo' => '60', 'Nombre_Cuenta' => 'Compras de Mercaderías e Insumos',  'Tipo' => 'Costo'],
            ['Codigo' => '63', 'Nombre_Cuenta' => 'Costo de Producción / Servicios',   'Tipo' => 'Costo'],
            ['Codigo' => '69', 'Nombre_Cuenta' => 'Costo de Ventas',                   'Tipo' => 'Costo'],

            // ── GASTO ─────────────────────────────────────────────
            ['Codigo' => '62', 'Nombre_Cuenta' => 'Gastos de Personal y Remuneraciones','Tipo' => 'Gasto'],
            ['Codigo' => '64', 'Nombre_Cuenta' => 'Gastos de Servicios',               'Tipo' => 'Gasto'],
            ['Codigo' => '65', 'Nombre_Cuenta' => 'Gastos de Ventas y Marketing',      'Tipo' => 'Gasto'],
            ['Codigo' => '67', 'Nombre_Cuenta' => 'Gastos Financieros (Intereses)',    'Tipo' => 'Gasto'],
            ['Codigo' => '68', 'Nombre_Cuenta' => 'Depreciación de Activos',           'Tipo' => 'Gasto'],
            ['Codigo' => '87', 'Nombre_Cuenta' => 'Impuesto a la Renta (29.5%)',       'Tipo' => 'Gasto'],
        ];

        foreach ($cuentas as $cuenta) {
            DB::table('cuenta_contable')->updateOrInsert(
                ['Codigo' => $cuenta['Codigo']],
                array_merge($cuenta, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command->info('✅ Plan de Cuentas PCGE cargado: ' . count($cuentas) . ' cuentas.');
    }
}