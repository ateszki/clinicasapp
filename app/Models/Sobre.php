<?php

class Sobre extends Maestro {

	protected $table = 'sobres'; 	

	protected $fillable = array(
		'historia_clinica',
		'fichero_id',
		'paciente_id',
		'archivado',
		);


	public $rules = array(
                        'historia_clinica' => 'Required|integer',
			'fichero_id' => 'Required|integer|exists:ficheros,id',
			'paciente_id' => 'Required|integer|exists:pacientes,id',
			'identificador' => 'required|boolean',
                );
	public function paciente(){
		return $this->belongsTo('Paciente');
	}
	public function fichero(){
		return $this->belongsTo('Fichero');
	}
	public function getCentroAttribute(){
		return $this->fichero->nombre_centro;
	}
	public function getUbicacionAttribute(){
		return $this->fichero->ubicacion;
	}
	protected $appends = ['centro','ubicacion'];
	
}
