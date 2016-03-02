<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterReglasAuditoria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reglas_auditoria', function (Blueprint $table) {
            $table->boolean('automatico')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reglas_auditoria', function (Blueprint $table) {
            $table->dropColumn('automatico');
        });
    }
}
