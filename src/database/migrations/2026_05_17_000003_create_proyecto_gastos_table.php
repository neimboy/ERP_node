<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyecto_gastos', function (Blueprint $table) {
            $table->id('Id_Gasto');
            $table->foreignId('Id_Proyecto')->constrained('proyectos', 'Id_Proyecto')->cascadeOnDelete();
            $table->string('Descripcion', 255);
            $table->decimal('Monto', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyecto_gastos');
    }
};
