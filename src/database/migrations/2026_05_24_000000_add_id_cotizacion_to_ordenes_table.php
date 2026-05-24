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
        if (Schema::hasTable('ordenes') && Schema::hasTable('cotizaciones')) {
            Schema::table('ordenes', function (Blueprint $table) {
                if (!Schema::hasColumn('ordenes', 'Id_Cotizacion')) {
                    $table->unsignedBigInteger('Id_Cotizacion')->nullable()->after('Id_Cliente');
                    $table->foreign('Id_Cotizacion')->references('Id_Cotizacion')->on('cotizaciones')->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('ordenes')) {
            Schema::table('ordenes', function (Blueprint $table) {
                if (Schema::hasColumn('ordenes', 'Id_Cotizacion')) {
                    // El nombre del índice se genera automáticamente por Laravel
                    $table->dropForeign(['Id_Cotizacion']);
                    $table->dropColumn('Id_Cotizacion');
                }
            });
        }
    }
};
