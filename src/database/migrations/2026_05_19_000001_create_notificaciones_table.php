<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id('Id_Notificacion');
            $table->foreignId('Id_Producto')->constrained('productos', 'Id_Producto');
            $table->integer('Cantidad_Requerida');
            $table->foreignId('Id_Proyecto')->nullable()->constrained('proyectos', 'Id_Proyecto');
            $table->text('Mensaje')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
