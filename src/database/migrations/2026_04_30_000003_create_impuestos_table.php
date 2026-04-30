<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('impuestos', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->string('Nombre');
            $table->decimal('Porcentaje', 8, 2)->default(0);
            $table->string('Tipo')->nullable();
            $table->boolean('Activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('impuestos');
    }
};
