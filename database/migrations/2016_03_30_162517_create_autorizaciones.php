<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutorizaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autorizaciones', function (Blueprint $table) {
		$table->increments('id');
		$table->integer('user_id_autorizado')->unsigned();
		$table->integer('user_id_autorizante')->unsigned();
		$table->integer('role_id')->unsigned();
		$table->datetime('hasta');
		$table->timestamps();
		$table->foreign('user_id_autorizado')->references('id')->on('users');
		$table->foreign('user_id_autorizante')->references('id')->on('users');
		$table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('autorizaciones');
    }
}
