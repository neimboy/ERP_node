<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_oportunidades', function (Blueprint $table) {
            $table->id('Id_Detalle');
            $table->unsignedBigInteger('Id_Oportunidad');
            $table->unsignedBigInteger('Id_Producto');
            $table->integer('Cantidad')->default(1);
            $table->decimal('Precio_Unitario', 15, 2)->default(0);
            $table->decimal('Descuento', 8, 2)->default(0);
            $table->decimal('Total', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('Id_Oportunidad')
                  ->references('Id_Oportunidad')
                  ->on('oportunidades_crm')
                  ->onDelete('cascade');

            $table->foreign('Id_Producto')
                  ->references('Id_Producto')
                  ->on('productos')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_oportunidades');
    }
};
