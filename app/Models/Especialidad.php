<?php

class Especialidad extends Maestro {

	protected $table = 'especialidades'; 	

	protected $fillable = array(
		'especialidad',
		'lapso',
		'valor',
		'requiere_derivacion',
		'genera_agendas',
		'reserva_turnos_cada',
		'libera_faltando',
		);


	public $rules = array(
                        'especialidad' => 'Required|Max:50',
			'lapso' => 'Required|integer',
			'valor' => 'Required|integer',
			'requiere_derivacion' => 'Required|integer|in:0,1',
			'genera_agendas'=>'boolean',
			'libera_faltando'=>'integer',
                );

	public function motivosTurnos(){
		return $this->hasMany('MotivoTurno');
	}

	
}
