<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            if (!Schema::hasColumn('facturas', 'Id_Cliente')) {
                $table->unsignedBigInteger('Id_Cliente')->nullable()->after('Id_Orden');
            }
            if (!Schema::hasColumn('facturas', 'Numero_Factura')) {
                $table->string('Numero_Factura', 100)->nullable()->after('Id_Cliente');
            }
            if (!Schema::hasColumn('facturas', 'Subtotal')) {
                $table->decimal('Subtotal', 15, 2)->nullable()->after('Numero_Factura');
            }
            if (!Schema::hasColumn('facturas', 'IGV')) {
                $table->decimal('IGV', 15, 2)->nullable()->after('Subtotal');
            }
            if (!Schema::hasColumn('facturas', 'Estado')) {
                $table->string('Estado', 50)->nullable()->after('IGV');
            }

            // Añadimos la FK a clientes si la columna existe y la tabla clientes también
            if (Schema::hasColumn('facturas', 'Id_Cliente') && Schema::hasTable('clientes')) {
                try {
                    $table->foreign('Id_Cliente')->references('Id_Cliente')->on('clientes')->onDelete('set null');
                } catch (\Exception $e) {
                    // En entornos con datos legacy la FK puede fallar; no interrumpimos la migración
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            if (Schema::hasColumn('facturas', 'Estado')) {
                $table->dropColumn('Estado');
            }
            if (Schema::hasColumn('facturas', 'IGV')) {
                $table->dropColumn('IGV');
            }
            if (Schema::hasColumn('facturas', 'Subtotal')) {
                $table->dropColumn('Subtotal');
            }
            if (Schema::hasColumn('facturas', 'Numero_Factura')) {
                $table->dropColumn('Numero_Factura');
            }
            if (Schema::hasColumn('facturas', 'Id_Cliente')) {
                // intentamos quitar la FK si existe primero
                try {
                    $table->dropForeign(['Id_Cliente']);
                } catch (\Exception $e) {
                }
                $table->dropColumn('Id_Cliente');
            }
        });
    }
};
