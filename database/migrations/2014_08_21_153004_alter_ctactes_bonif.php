<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCtactesBonif extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ctactes', function(Blueprint $table)
		{
			DB::statement('ALTER TABLE ctactes MODIFY COLUMN porc_bonificacion decimal(4,2)');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ctactes', function(Blueprint $table)
		{
			DB::statement('ALTER TABLE ctactes MODIFY COLUMN porc_bonificacion decimal(3,2)');
			//
		});
	}

}
