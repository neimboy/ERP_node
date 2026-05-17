<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id('Id_Movimiento');
            $table->foreignId('Id_Producto')->constrained('productos', 'Id_Producto');
            $table->foreignId('Id_Proyecto')->nullable()->constrained('proyectos', 'Id_Proyecto')->nullOnDelete();
            $table->enum('Tipo', ['salida_produccion', 'entrada_devolucion']);
            $table->integer('Cantidad');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
