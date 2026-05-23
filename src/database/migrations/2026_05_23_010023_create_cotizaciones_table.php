<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id('Id_Cotizacion');
            $table->unsignedBigInteger('Id_Cliente');
            $table->unsignedBigInteger('Id_Oportunidad')->nullable();

            // Columnas en mayúsculas para alinear con los modelos existentes
            $table->dateTime('Fecha');
            $table->dateTime('Fecha_Vencimiento')->nullable();
            $table->decimal('Subtotal', 15, 2)->default(0);
            $table->decimal('Impuesto', 15, 2)->default(0);
            $table->decimal('Total', 15, 2)->default(0);
            $table->enum('Estado', ['BORRADOR','ENVIADA','ACEPTADA','RECHAZADA'])->default('BORRADOR');
            $table->timestamps();

            $table->foreign('Id_Cliente')
                  ->references('Id_Cliente')
                  ->on('clientes')
                  ->onDelete('cascade');

            // FK apuntando a la tabla CRM de oportunidades
            $table->foreign('Id_Oportunidad')
                  ->references('Id_Oportunidad')
                  ->on('oportunidades_crm')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
