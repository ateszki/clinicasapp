<?php

class ReglasAuditoria extends Maestro {

	protected $table = 'reglas_auditoria'; 	

	protected $fillable = array(
		'codigo',
		'descripcion',
		'automatico',
		);


	public $rules = array(
                        'codigo' => 'Required|Max:2',
			'descripcion' => 'Required|max:255',
			'automatico' => 'boolean',
                );

	
}
