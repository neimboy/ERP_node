<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyecto_productos', function (Blueprint $table) {
            $table->id('Id_Proyecto_Producto');
            $table->foreignId('Id_Proyecto')->constrained('proyectos', 'Id_Proyecto')->cascadeOnDelete();
            $table->foreignId('Id_Producto')->constrained('productos', 'Id_Producto');
            $table->integer('Cantidad')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyecto_productos');
    }
};
