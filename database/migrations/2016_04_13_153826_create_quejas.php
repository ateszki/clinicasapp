<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuejas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quejas', function (Blueprint $table) {
		$table->increments('id');
		$table->integer('paciente_id')->unsigned();
		$table->string('motivo');
		$table->date('fecha');
		$table->longText('queja');
		$table->timestamps();
		$table->foreign('paciente_id')->references('id')->on('pacientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('quejas');
    }
}
