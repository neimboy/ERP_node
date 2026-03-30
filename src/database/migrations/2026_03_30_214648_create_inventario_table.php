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
        Schema::create('inventario', function (Blueprint $table) {
            $table->unsignedBigInteger('Id_Producto');
            $table->unsignedBigInteger('Id_Almacen');

            $table->integer('Cantidad');
            $table->integer('Stock_Minimo')->nullable();

            // Definición de llave primaria compuesta
            $table->primary(['Id_Producto', 'Id_Almacen']);

            // Llaves foráneas
            $table->foreign('Id_Producto')->references('Id_Producto')->on('productos')->cascadeOnDelete();
            $table->foreign('Id_Almacen')->references('Id_Almacen')->on('almacenes')->cascadeOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario');
    }
};