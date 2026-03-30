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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id('Id_Factura');
            $table->foreignId('Id_Orden')->constrained('ordenes', 'Id_Orden');
            $table->date('Fecha');
            $table->decimal('Total', 15, 2)->nullable();
            $table->string('Estado_Pago', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};