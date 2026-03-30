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
        Schema::create('productos', function (Blueprint $table) {
            $table->id('Id_Producto');
            $table->string('Codigo', 50)->unique();
            $table->string('Nombre', 150);
            $table->decimal('Precio_Compra', 15, 2)->nullable();
            $table->decimal('Precio_Venta', 15, 2)->nullable();

            // Relaciones
            $table->foreignId('Id_Categoria')->constrained('categorias', 'Id_Categoria');
            $table->foreignId('Id_Proveedor')->constrained('proveedores', 'Id_Proveedor');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};