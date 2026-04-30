<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->unsignedBigInteger('Id_Cliente');
            $table->string('Titulo');
            $table->date('Fecha')->nullable();
            $table->integer('Validez_Dias')->default(30);
            $table->string('Estado')->default('Pendiente');
            $table->decimal('Total', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('Id_Cliente')->references('Id')->on('clientes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
