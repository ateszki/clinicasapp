<?php

class NormasAuditoria extends Maestro {

	protected $table = 'normas_auditoria'; 	

	protected $fillable = array(
		'descripcion',
		);


	public $rules = array(
			'descripcion' => 'Required|max:255',
                );

	public function detalle(){
		return $this->hasMany('NormasDetalle');
	}
	
}
