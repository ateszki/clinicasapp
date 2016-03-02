<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInconsistenciasAuditoria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inconsistencias_auditoria', function (Blueprint $table) {
		$table->increments('id');
		$table->integer('nomenclador_id')->unsigned();
		$table->integer('previo_id')->unsigned();
		$table->foreign('nomenclador_id')->references('id')->on('nomencladores');
		$table->foreign('previo_id')->references('id')->on('nomencladores');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('inconsistencias_auditoria');
    }
}
