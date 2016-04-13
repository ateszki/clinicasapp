<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCtactesTocket14 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ctactes', function (Blueprint $table) {
		DB::statement('ALTER TABLE ctactes MODIFY COLUMN ticket char(14)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ctactes', function (Blueprint $table) {
            //
        });
    }
}
