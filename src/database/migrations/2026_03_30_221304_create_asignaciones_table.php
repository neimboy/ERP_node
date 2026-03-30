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
    Schema::create('asignaciones', function (Blueprint $table) {
        $table->id('Id_Asignacion');
        $table->foreignId('Id_Empleado')->constrained('empleados', 'Id_Empleado');
        $table->foreignId('Id_Proyecto')->constrained('proyectos', 'Id_Proyecto');
        $table->integer('Horas_Asignadas')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaciones');
    }
};
