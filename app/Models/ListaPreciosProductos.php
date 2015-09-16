<?php

class ListaPreciosProductos extends Maestro {

	protected $table = 'listas_precios_productos'; 	

	protected $fillable = array(
		'codigo',
		'descripcion',
		'marca',
		'precio',
		);
	
	public $rules = array(
                        'codigo' => 'Required',
			'precio'=>'required|numeric',
	);

}
