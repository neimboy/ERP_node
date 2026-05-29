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
        Schema::table('detalle_orden', function (Blueprint $table) {
            if (!Schema::hasColumn('detalle_orden', 'Precio_Unitario')) {
                $table->decimal('Precio_Unitario', 15, 2)->nullable()->after('Precio');
            }

            if (!Schema::hasColumn('detalle_orden', 'Costo_Unitario')) {
                $table->decimal('Costo_Unitario', 15, 2)->nullable()->after('Precio_Unitario');
            }

            if (!Schema::hasColumn('detalle_orden', 'Descuento')) {
                $table->decimal('Descuento', 8, 2)->default(0)->after('Costo_Unitario');
            }

            if (!Schema::hasColumn('detalle_orden', 'Total')) {
                $table->decimal('Total', 15, 2)->nullable()->after('Descuento');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_orden', function (Blueprint $table) {
            if (Schema::hasColumn('detalle_orden', 'Total')) {
                $table->dropColumn('Total');
            }
            if (Schema::hasColumn('detalle_orden', 'Descuento')) {
                $table->dropColumn('Descuento');
            }
            if (Schema::hasColumn('detalle_orden', 'Costo_Unitario')) {
                $table->dropColumn('Costo_Unitario');
            }
            if (Schema::hasColumn('detalle_orden', 'Precio_Unitario')) {
                $table->dropColumn('Precio_Unitario');
            }
        });
    }
};
