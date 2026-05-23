<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oportunidades_crm', function (Blueprint $table) {
            $table->id('Id_Oportunidad');
            $table->unsignedBigInteger('Id_Cliente');

            $table->string('Titulo', 150);
            $table->text('Descripcion')->nullable();
            $table->decimal('Monto_Estimado', 15, 2)->default(0);
            $table->string('Estado', 50)->default('Prospecto');
            $table->date('Fecha_Cierre')->nullable();

            $table->timestamps();

            $table->foreign('Id_Cliente')
                  ->references('Id_Cliente')
                  ->on('clientes')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oportunidades');
    }
};
