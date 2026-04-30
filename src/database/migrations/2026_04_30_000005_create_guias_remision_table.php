<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('guias_remision', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('Id_Orden');
            $table->string('Numero_Guia')->nullable();
            $table->date('Fecha_Emision')->nullable();
            $table->string('Direccion_Origen')->nullable();
            $table->string('Direccion_Destino')->nullable();
            $table->string('Estado')->default('Pendiente');
            $table->timestamps();

            $table->foreign('Id_Orden')->references('Id')->on('ordenes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guias_remision');
    }
};
