<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListasHonorariosValores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listas_honorarios_valores', function (Blueprint $table) {
		$table->increments('id');
		$table->integer('lista_id')->unsigned();
		$table->decimal('valor',18,2);
		$table->integer('nomenclador_id')->unsigned();
		$table->date('vigencia');
		$table->foreign('lista_id','fk_lis_hon')->references('id')->on('listas_honorarios');
		$table->foreign('nomenclador_id')->references('id')->on('nomencladores');
		$table->unique(['lista_id','nomenclador_id','vigencia'],'ux_lis_nom_vig');
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
        Schema::drop('listas_honorarios_valores');
    }
}
