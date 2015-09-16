<?php

class Fichero extends Maestro {

	protected $table = 'ficheros'; 	

	protected $fillable = array(
		'nombre',
		'ubicacion',
		'centro_id',
		);


	public $rules = array(
                        'nombre' => 'Required|Min:3|Max:255',
			'centro_id' => 'Required|integer|exists:centros,id',
                        'ubicacion' => 'Min:3|Max:255',
                );

		public function centro(){
			return $this->belongsTo('Centro');
		}
		public function getNombreCentroAttribute(){
			return $this->centro->razonsocial;
		}	
		protected $appends =['nombre_centro'];
}
