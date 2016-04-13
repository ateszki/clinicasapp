<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CodigoMotivosTurnos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motivos_turnos', function (Blueprint $table) {
            $table->char('codigo',2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motivos_turnos', function (Blueprint $table) {
            $table->dropColumn('codigo');
        });
    }
}
