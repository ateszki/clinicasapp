<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListaPreciosProductos extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('listas_precios_productos', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('codigo')->unique();
			$table->string('descripcion')->nullable();
			$table->string('marca')->nullable();
			$table->decimal('precio',18,2)->default(0);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('listas_precios_productos');
	}

}
