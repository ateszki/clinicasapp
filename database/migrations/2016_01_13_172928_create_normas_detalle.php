<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNormasDetalle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('normas_detalle', function (Blueprint $table) {
		$table->increments('id');
		$table->integer('normas_auditoria_id')->unsigned();
		$table->integer('reglas_auditoria_id')->unsigned();
		$table->integer('nomenclador_id')->unsigned();
		$table->boolean('limite_edad');
		$table->integer('edad_desde')->nullable();
		$table->integer('edad_hasta')->nullable();
		$table->boolean('pediodo_garantia');
		$table->integer('meses_garantia')->nulalble();
		$table->integer('cantidad_sesion')->default(1);
		$table->foreign('reglas_auditoria_id')->references('id')->on('reglas_auditoria');
		$table->foreign('nomenclador_id')->references('id')->on('nomencladores');
		$table->foreign('normas_auditoria_id')->references('id')->on('normas_auditoria');
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
        Schema::drop('normas_detalle');
    }
}
