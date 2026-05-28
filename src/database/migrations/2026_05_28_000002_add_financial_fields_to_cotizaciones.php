<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cotizaciones')) {
            Schema::table('cotizaciones', function (Blueprint $table) {
                if (!Schema::hasColumn('cotizaciones', 'Costos_Directos')) {
                    $table->decimal('Costos_Directos', 15, 2)->default(0)->after('Fecha_Vencimiento');
                }
                if (!Schema::hasColumn('cotizaciones', 'Gastos_Generales')) {
                    $table->decimal('Gastos_Generales', 15, 2)->default(0)->after('Costos_Directos');
                }
                if (!Schema::hasColumn('cotizaciones', 'Utilidad')) {
                    $table->decimal('Utilidad', 15, 2)->default(0)->after('Gastos_Generales');
                }
                // Subtotal/Impuesto/Total may exist already; keep checks
                if (!Schema::hasColumn('cotizaciones', 'Subtotal')) {
                    $table->decimal('Subtotal', 15, 2)->default(0)->after('Utilidad');
                }
                if (!Schema::hasColumn('cotizaciones', 'Impuesto')) {
                    $table->decimal('Impuesto', 15, 2)->default(0)->after('Subtotal');
                }
                if (!Schema::hasColumn('cotizaciones', 'Total')) {
                    $table->decimal('Total', 15, 2)->default(0)->after('Impuesto');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cotizaciones')) {
            Schema::table('cotizaciones', function (Blueprint $table) {
                foreach (['Costos_Directos','Gastos_Generales','Utilidad','Subtotal','Impuesto','Total'] as $col) {
                    if (Schema::hasColumn('cotizaciones', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
