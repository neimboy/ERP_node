<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notas_credito', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('Id_Factura');
            $table->date('Fecha')->nullable();
            $table->decimal('Monto', 15, 2)->default(0);
            $table->text('Motivo')->nullable();
            $table->string('Estado')->default('Pendiente');
            $table->timestamps();

            $table->foreign('Id_Factura')->references('Id')->on('facturas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notas_credito');
    }
};
