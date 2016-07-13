<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterListaspreciosnomencladorPrecioNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listas_precios_nomenclador', function (Blueprint $table) {
            DB::statement("ALTER TABLE `listas_precios_nomenclador` CHANGE `precio` `precio` DECIMAL(10,2) NULL DEFAULT '0.00';");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listas_precios_nomenclador', function (Blueprint $table) {
            DB::statement("ALTER TABLE `listas_precios_nomenclador` CHANGE `precio` `precio` DECIMAL(10,2) NOT NULL DEFAULT '0.00';");
        });
    }
}
