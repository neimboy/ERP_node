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
        Schema::create('oportunidades_crm', function (Blueprint $table) {
            $table->id('Id_Oportunidad');
            $table->unsignedBigInteger('Id_Cliente');
            $table->string('Titulo');
            $table->text('Descripcion')->nullable();
            $table->decimal('Monto_Estimado', 14, 2)->default(0);
            $table->enum('Estado', ['Prospecto', 'Negociación', 'Cerrado'])->default('Prospecto');
            $table->date('Fecha_Cierre')->nullable();
            $table->timestamps();

            $table->foreign('Id_Cliente')->references('Id_Cliente')->on('clientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oportunidades_crm');
    }
};
