<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSobres extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sobres', function (Blueprint $table) {
            $table->increments('id');
	    $table->bigInteger('historia_clinica');
	    $table->integer('fichero_id')->unsigned()->index();
	    $table->integer('paciente_id')->unsigned()->index();
	    $table->boolean('archivado')->default(false);
            $table->timestamps();
	    $table->foreign('fichero_id')->references('id')->on('ficheros');
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
        Schema::drop('sobres');
    }
}
