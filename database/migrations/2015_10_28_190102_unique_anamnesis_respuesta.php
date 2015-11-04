<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UniqueAnamnesisRespuesta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anamnesis_respuestas', function (Blueprint $table) {
            $table->unique(['paciente_id','anamnesis_pregunta_id'],'unique_anamnesis_respuesta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anamnesis_respuestas', function (Blueprint $table) {
            $table->dropUnique('unique_anamnesis_respuesta');
        });
    }
}
