<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: añade la columna Estado a la tabla periodos.
 * Ejecutar: php artisan migrate
 *
 * Valores posibles: 'Abierto' | 'Cerrado'
 * Por defecto todos los períodos existentes quedan 'Abierto'.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('periodos', function (Blueprint $table) {
            // Solo agregar si no existe (para entornos ya migrados)
            if (!Schema::hasColumn('periodos', 'Estado')) {
                $table->string('Estado', 10)->default('Abierto')->after('Mes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('periodos', function (Blueprint $table) {
            $table->dropColumn('Estado');
        });
    }
};