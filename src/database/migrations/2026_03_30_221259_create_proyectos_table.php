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
    Schema::create('proyectos', function (Blueprint $table) {
        $table->id('Id_Proyecto');
        $table->foreignId('Id_Cliente')->constrained('clientes', 'Id_Cliente');
        $table->string('Nombre', 150);
        $table->date('Fecha_Inicio')->nullable();
        $table->date('Fecha_Fin')->nullable();
        $table->string('Estado', 50)->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
