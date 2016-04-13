<?php

class Paciente extends Maestro {

	protected $table = 'pacientes'; 	

	protected $fillable = array(
		'apellido',
		'nombres',
		'fecha_nacimiento',
		'tipo_documento',
		'nro_documento',
		'sexo',
		'pais_nacimiento_id',
		'iva_id',
		'cuit',
		'domicilio',
		'localidad',
		'provincia_id',
		'pais_id',
		'codigopostal',
		'telefono',
		'telefono2',
		'celular',
		'email',
		'vip'
		);


	public $rules = array(
                        'nombres' => 'Required|Min:3|Max:50',
                        'apellido' => 'Required|Min:3|Max:50',
                        'fecha_nacimiento'     => 'Date',
                        'tipo_documento' => 'Required|In:CI,DU,LE,LC,PS',
			'nro_documento' => 'Required|integer',
                        'sexo' => 'Required|In:M,F,m,f',
			'iva_id' => 'Required|integer|exists:iva,id',
			'cuit' => 'max:11',
			'domicilio' => 'max:50',
			'localidad' => 'max:50',
			'provincia_id'=>'required|integer|exists:provincias,id',
			'pais_id'=>'required|integer|exists:paises,id',
			'vip'=>'boolean',
			'email'=>'email',
                );

	public $validator_messages = [
		    'email' => 'Ingrese una dirección de email válido.',
		    ];


	protected $appends = ['presento_queja'];

	public function prepagas(){
		return $this->belongsToMany('Prepaga');
	}

	public function observaciones(){
		return $this->hasMany('PacienteObservacion');
	}	
	
	public function presupuestos(){
		return $this->hasManyThrough('Presupuesto','PacientePrepaga');
	}

	public function fichados(){
		return $this->hasMany('Fichado');
	}

	public function fichadosItems(){
		return $this->hasManyThrough('FichadoItem','Fichado');
	}
	public function turnos(){
		return $this->hasManyThrough('Turno','PacientePrepaga');
	}
	public function planes_tratamientos(){
		return $this->hasMany('PlanTratamiento');
	}
	public function sobres(){
		return $this->hasMany('Sobre');
	}
	public function quejas(){
		return $this->hasMany('Queja');
	}
	public function anamnesis_respuestas(){
		return $this->hasMany('AnamnesisRespuesta');
	}

	public function tratamientos(){
		return DB::select("SELECT tr.*
			FROM tratamientos tr
			INNER JOIN turnos tu ON tr.turno_id = tu.id
			INNER JOIN paciente_prepaga pp ON tu.paciente_prepaga_id = pp.id
			WHERE
			pp.paciente_id = ? ",[$this->id]);
		
	}

	public function tieneFichados(){
		return count(DB::select("SELECT tr . *
			FROM tratamientos tr
			INNER JOIN turnos tu ON tr.turno_id = tu.id
			INNER JOIN paciente_prepaga pp ON tu.paciente_prepaga_id = pp.id
			INNER JOIN agendas a on tu.agenda_id = a.id
			WHERE
			tr.nomenclador_id in (select id from nomencladores where `genera_odontograma` = 1)
			and pp.paciente_id = ? 
			and a.fecha >= DATE_ADD(now(),INTERVAL -180 DAY)
			",[$this->id]));
			
		//return ($this->fichados()->where('fecha_emision','>=','DATE_ADD(now(),INTERVAL -180 DAY)')->count());
	}

	public function getPresentoQuejaAttribute(){
		return count($this->quejas);
	}

	public function tieneHIV(){
		$ars = $this->anamnesis_respuestas()->where('anamnesis_pregunta_id','=',19)->get();
		$salida = false;
		foreach ($ars as $ar){
			$salida = ($ar->respuesta == 'SI')?true:false;
		}
		return $salida;
	}
}
