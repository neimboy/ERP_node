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
    Schema::create('detalle_orden', function (Blueprint $table) {
        $table->id('Id_Detalle');
        $table->foreignId('Id_Orden')->constrained('ordenes', 'Id_Orden')->cascadeOnDelete();
        $table->foreignId('Id_Producto')->constrained('productos', 'Id_Producto');
        $table->integer('Cantidad');
        $table->decimal('Precio', 15, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_orden');
    }
};
