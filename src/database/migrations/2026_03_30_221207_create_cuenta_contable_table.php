<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::create('cuenta_contable', function (Blueprint $table) {
        $table->id('Id_Cuenta');
        $table->string('Codigo', 20)->unique();
        $table->string('Nombre_Cuenta', 150);
        $table->string('Tipo', 50);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuenta_contable');
    }
};
