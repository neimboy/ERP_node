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
    Schema::create('contratos', function (Blueprint $table) {
        $table->id('Id_Contrato');
        $table->foreignId('Id_Empleado')->constrained('empleados', 'Id_Empleado');
        $table->foreignId('Id_Puesto')->constrained('puestos', 'Id_Puesto');
        $table->date('Fecha_Inicio');
        $table->date('Fecha_Fin')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
