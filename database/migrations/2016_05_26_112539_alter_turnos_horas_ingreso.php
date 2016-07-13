<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTurnosHorasIngreso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('turnos', function (Blueprint $table) {
		$table->time('hora_ingreso_clinica')->nullable();
		$table->time('hora_ingreso_consultorio')->nullable();
		$table->time('hora_egreso_consultorio')->nullable();
		$table->char('falta_registro_ingresos',3)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('turnos', function (Blueprint $table) {
            $table->dropColumn(['hora_ingreso_clinica','hora_ingreso_consultorio','hora_egreso_consultorio','falta_registro_ingresos']);
        });
    }
}
