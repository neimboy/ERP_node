<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('detalle_cotizaciones')) {
            Schema::table('detalle_cotizaciones', function (Blueprint $table) {
                if (!Schema::hasColumn('detalle_cotizaciones', 'Costo_Unitario')) {
                    $table->decimal('Costo_Unitario', 15, 2)->default(0)->after('Precio_Unitario');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('detalle_cotizaciones')) {
            Schema::table('detalle_cotizaciones', function (Blueprint $table) {
                if (Schema::hasColumn('detalle_cotizaciones', 'Costo_Unitario')) {
                    $table->dropColumn('Costo_Unitario');
                }
            });
        }
    }
};
