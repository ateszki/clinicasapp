<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNormasDetalle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('normas_detalle', function (Blueprint $table) {
		$table->boolean('requiere_odontograma')->default(0);
		$table->integer('cantidad_rx')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('normas_detalle', function (Blueprint $table) {
            $table->dropColumn(['requiere_odontograma','cantidad_rx']);
        });
    }
}
