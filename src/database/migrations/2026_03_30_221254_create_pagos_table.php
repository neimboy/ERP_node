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
    Schema::create('pagos', function (Blueprint $table) {
        $table->id('Id_Pago');
        $table->foreignId('Id_Factura')->constrained('facturas', 'Id_Factura');
        $table->date('Fecha')->nullable();
        $table->decimal('Monto', 15, 2)->nullable();
        $table->string('Metodo', 50)->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
