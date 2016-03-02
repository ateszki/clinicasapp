<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNormasDetalle1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('normas_detalle', function (Blueprint $table) {
		$table->char('nivel_garantia',1)->nullable();
		$table->boolean('paga_rx')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('normas_detalle', function (Blueprint $table) {
            $table->dropColumn(['nivel_garantia','paga_rx']);
        });
    }
}
