<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPacPrepGravado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paciente_prepaga', function (Blueprint $table) {
            $table->boolean('gravado')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paciente_prepaga', function (Blueprint $table) {
            $table->dropColumn('gravado');
        });
    }
}
