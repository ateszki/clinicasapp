<?php

class Queja extends Maestro {

	protected $table = 'quejas'; 	

	protected $fillable = array(
		'paciente_id',
		'motivo',
		'fecha',
		'queja',
		);


	public $rules = array(
                        'motivo' => 'Required|Min:3',
			'paciente_id' => 'required|integer|exists:pacientes,id',
			'fecha' => 'required|date',
			'queja' => 'required|min:3',
                );


	public function paciente(){
		return $this->belongsTo('Paciente');
	}	
}
