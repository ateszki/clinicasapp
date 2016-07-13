<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserOdontoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
		$table->integer('odontologo_id')->unsigned()->nullable();
		$table->foreign('odontologo_id')->references('id')->on('odontologos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
		$table->dropForeign('users_odontologo_id_foreign');
		$table->dropColumn('odontologo_id');
        });
    }
}
