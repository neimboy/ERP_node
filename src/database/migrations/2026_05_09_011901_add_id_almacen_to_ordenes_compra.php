<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('ordenes_compra', function (Blueprint $table) {
            $table->unsignedBigInteger('Id_Almacen')->nullable()->after('Id_Proveedor');
            $table->foreign('Id_Almacen')->references('Id_Almacen')->on('almacenes');
        });
    }

    public function down()
    {
        Schema::table('ordenes_compra', function (Blueprint $table) {
            $table->dropForeign(['Id_Almacen']);
            $table->dropColumn('Id_Almacen');
        });
    }

};
