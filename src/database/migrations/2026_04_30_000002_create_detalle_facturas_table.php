<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detalle_facturas', function (Blueprint $table) {
            $table->id('Id_Detalle');
            $table->unsignedBigInteger('Id_Factura');
            $table->unsignedBigInteger('Id_Producto');
            $table->integer('Cantidad')->default(1);
            $table->decimal('Precio_Unitario', 15, 2)->default(0);
            $table->decimal('Subtotal', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('Id_Factura')->references('Id_Factura')->on('facturas')->onDelete('cascade');
            $table->foreign('Id_Producto')->references('Id_Producto')->on('productos')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_facturas');
    }
};
