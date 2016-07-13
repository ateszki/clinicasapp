<?php
use Carbon\Carbon;

class Paciente extends Maestro {
	protected $table = 'pacientes'; 	

	public function newFromBuilder($attributes = array(),$connection=NULL)  {
	        $model =  parent::newFromBuilder($attributes); // Eloquent
		if($model->fecha_nacimiento == '-0001-11-30 00:00:00'){
			$model->fecha_nacimiento = NULL;
		}
		if($model->created_at == '-0001-11-30 00:00:00'){
			$model->created_at = Carbon::now();
		}
		if($model->updated_at == '-0001-11-30 00:00:00'){
			$model->updated_at = Carbon::now();
		}

		return $model;
	}

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


	protected $appends = ['presento_queja','tiene_hiv','edad'];

	protected $dates = ['created_at','updated_at','fecha_nacimiento'];

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
		return (count(DB::select("select id from quejas where paciente_id = ?",[$this->id]))> 0)?"1":"0";
	}

	public function getTieneHivAttribute(){
		//return (string) count(DB::select("select id from anamnesis_respuestas where anamnesis_pregunta_id = 19 and respuesta = 'SI' and paciente_id = ?",[$this->id])); 
		return (string) count(DB::select("select id from anamnesis_respuestas where anamnesis_pregunta_id = 19 and respuesta = 'SI' and paciente_id = ?",[$this->id])); 
	}
	public function getEdadAttribute(){
		return ($this->fecha_nacimiento == NULL)?"0":(string) $this->fecha_nacimiento->age;
	}
	
	public function getEsquema(){
		$esquema = parent::getEsquema();
		$esquema[] = array(
			"Field"=> "presento_queja",
			"Type"=> "tinyint(1)",
			"Null"=> "YES",
			"Key"=> "",
			"Default"=>"0",
			"Extra"=> ""
		);
		$esquema[] = array(
			"Field"=> "tiene_hiv",
			"Type"=> "tinyint(1)",
			"Null"=> "YES",
			"Key"=> "",
			"Default"=>"0",
			"Extra"=> ""
		);
		$esquema[] = array(
			"Field"=> "edad",
			"Type"=> "int (11)",
			"Null"=> "YES",
			"Key"=> "",
			"Default"=>"0",
			"Extra"=> ""
		);
		//dd($esquema);
		return $esquema;
	}
}
