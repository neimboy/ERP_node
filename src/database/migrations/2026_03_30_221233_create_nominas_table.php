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
    Schema::create('nominas', function (Blueprint $table) {
        $table->id('Id_Nomina');
        $table->foreignId('Id_Empleado')->constrained('empleados', 'Id_Empleado');
        $table->foreignId('Id_Periodo')->constrained('periodos', 'Id_Periodo');
        $table->decimal('Total_Bruto', 15, 2)->nullable();
        $table->decimal('Total_Deducciones', 15, 2)->nullable();
        $table->decimal('Neto_Pagar', 15, 2)->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nominas');
    }
};
