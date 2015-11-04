<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNombecladorIva extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nomencladores', function (Blueprint $table) {
            $table->decimal('tasaiva',5,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nomencladores', function (Blueprint $table) {
            $table->dropColumn('tasaiva');
        });
    }
}
