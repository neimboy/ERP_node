<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void {
    Schema::create('asiento_detalle', function (Blueprint $table) {
        $table->id('Id_Detalle');
        $table->foreignId('Id_Asiento')->constrained('asientos', 'Id_Asiento')->cascadeOnDelete();
        $table->foreignId('Id_Cuenta')->constrained('cuenta_contable', 'Id_Cuenta');
        $table->decimal('Debe', 15, 2)->default(0.00);
        $table->decimal('Haber', 15, 2)->default(0.00);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asiento_detalle');
    }
};
