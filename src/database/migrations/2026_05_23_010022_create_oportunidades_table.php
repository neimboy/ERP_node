<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oportunidades', function (Blueprint $table) {
            $table->id('Id_Oportunidad'); // Siguiendo tu misma convención de mayúsculas
            $table->unsignedBigInteger('Id_Cliente'); // Llave foránea

            $table->string('titulo', 150);
            $table->decimal('monto_estimado', 10, 2)->default(0);
            $table->string('etapa', 50)->default('Prospecto'); // Prospecto, Contactado, Propuesta, Negociacion, Ganado, Perdido

            $table->timestamps();

            // Relación con tu tabla de clientes del módulo de ventas
            $table->foreign('Id_Cliente')
                  ->references('Id_Cliente')
                  ->on('clientes')
                  ->onDelete('cascade'); // Si borras al cliente, se borran sus oportunidades
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oportunidades');
    }
};
