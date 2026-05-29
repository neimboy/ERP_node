<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('detalle_facturas', function (Blueprint $table) {
            if (!Schema::hasColumn('detalle_facturas', 'Costo_Unitario')) {
                $table->decimal('Costo_Unitario', 15, 2)->default(0)->after('Precio_Unitario');
            }
            if (!Schema::hasColumn('detalle_facturas', 'Descuento')) {
                $table->decimal('Descuento', 8, 2)->default(0)->after('Costo_Unitario');
            }
            if (!Schema::hasColumn('detalle_facturas', 'Total')) {
                $table->decimal('Total', 15, 2)->nullable()->after('Descuento');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_facturas', function (Blueprint $table) {
            if (Schema::hasColumn('detalle_facturas', 'Total')) {
                $table->dropColumn('Total');
            }
            if (Schema::hasColumn('detalle_facturas', 'Descuento')) {
                $table->dropColumn('Descuento');
            }
            if (Schema::hasColumn('detalle_facturas', 'Costo_Unitario')) {
                $table->dropColumn('Costo_Unitario');
            }
        });
    }
};
