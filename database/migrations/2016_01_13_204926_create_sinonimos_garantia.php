<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSinonimosGarantia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sinonimos_garantia', function (Blueprint $table) {
            $table->increments('id');
		$table->integer('nomenclador_id')->unsigned();
	    $table->integer('sinonimo_id')->unsigned();
	    $table->foreign('nomenclador_id')->references('id')->on('nomencladores');
	    $table->foreign('sinonimo_id')->references('id')->on('nomencladores');
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
        Schema::drop('sinonimos_garantia');
    }
}
