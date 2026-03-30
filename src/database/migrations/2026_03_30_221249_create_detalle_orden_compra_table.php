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
    Schema::create('detalle_orden_compra', function (Blueprint $table) {
        $table->id('Id_Detalle');
        $table->foreignId('Id_Orden_Compra')->constrained('ordenes_compra', 'Id_Orden_Compra')->cascadeOnDelete();
        $table->foreignId('Id_Producto')->constrained('productos', 'Id_Producto');
        $table->integer('Cantidad');
        $table->decimal('Costo', 15, 2)->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_orden_compra');
    }
};
