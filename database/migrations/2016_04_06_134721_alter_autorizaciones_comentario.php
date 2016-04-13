<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAutorizacionesComentario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('autorizaciones', function (Blueprint $table) {
		$table->string('comentario')->nullable();
		DB::statement('ALTER TABLE `autorizaciones` MODIFY `user_id_autorizante` INTEGER UNSIGNED NULL;');
		DB::statement('ALTER TABLE `autorizaciones` MODIFY `hasta` datetime NULL;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('autorizaciones', function (Blueprint $table) {
            $table->dropColumn('comentario');
        });
    }
}
