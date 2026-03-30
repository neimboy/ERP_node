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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id('Id_Empleado');
            $table->string('DNI', 15)->unique();
            $table->string('Nombre', 150);
            $table->string('Correo', 150)->nullable()->unique();
            $table->string('Telefono', 20)->nullable();
            $table->date('Fecha_Ingreso');
            $table->boolean('Estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};