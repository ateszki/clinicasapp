<?php

class Agenda extends Maestro {

	protected $table = 'agendas'; 	

	protected $fillable = array(
		'centro_odontologo_especialidad_id',
		'fecha',
		'odontologo_efector_id',
		'habilitado_turnos',
		'observaciones',
		);

	public $rules = array(
                        'centro_odontologo_especialidad_id' => 'Required|exists:centros_odontologos_especialidades,id',
			'fecha' => 'Required|date',
			'odontologo_efector_id' => 'Required|exists:odontologos,id',
			'habilitado_turnos' => 'Required|integer|in:1,0',
			'observaciones' => 'Max:250',
                );

	public function turnos(){
		return $this->hasMany('Turno');
	}
	public function centroOdontologoEspecialidad(){
		return $this->belongsTo('CentroOdontologoEspecialidad');
	}

	
	public function vistaTurnos(){
		return DB::table('turnos')
			->leftJoin('motivos_turnos','turnos.motivo_turno_id','=','motivos_turnos.id')
                     ->leftJoin('paciente_prepaga','turnos.paciente_prepaga_id','=','paciente_prepaga.id')
			->leftJoin('pacientes','paciente_prepaga.paciente_id','=','pacientes.id')
->leftJoin('prepagas','paciente_prepaga.prepaga_id','=','prepagas.id')
->leftJoin('agendas','turnos.agenda_id','=','agendas.id')
->leftJoin('centros_odontologos_especialidades','agendas.centro_odontologo_especialidad_id','=','centros_odontologos_especialidades.id')
->leftJoin('odontologos','centros_odontologos_especialidades.odontologo_id','=','odontologos.id')
->leftJoin('especialidades','centros_odontologos_especialidades.especialidad_id','=','especialidades.id')
->leftJoin('centros','centros_odontologos_especialidades.centro_id','=','centros.id')
			->select(DB::raw("turnos.id,turnos.presente,turnos.hora_desde,turnos.hora_hasta,turnos.estado,turnos.tipo_turno,turnos.derivado_por,turnos.piezas,motivos_turnos.motivo,
							turnos.observaciones as obs_turno,turnos.paciente_prepaga_id, prepagas.codigo, prepagas.razon_social,pacientes.id as paciente_id,concat(pacientes.apellido,';',
							pacientes.nombres) as nombre,pacientes.vip,pacientes.tipo_documento,pacientes.nro_documento,pacientes.fecha_nacimiento as fecha_nacimiento_paciente,
							centros_odontologos_especialidades.cant_max_entreturnos,centros_odontologos_especialidades.entreturnos_desde,centros_odontologos_especialidades.entreturnos_hasta,
							centros_odontologos_especialidades.turno,centros_odontologos_especialidades.observaciones as obs_dia,
							agendas.id as agenda_id,agendas.habilitado_turnos as habilitada,agendas.observaciones as obs_agenda,prepagas.id as prepaga_id,turnos.fuera_de_agenda,
							centros.razonsocial as centro, especialidades.especialidad, concat(odontologos.apellido,', ',odontologos.nombres) as odontologo,odontologos.matricula,turnos.hora_ingreso_clinica,
							turnos.hora_ingreso_consultorio,turnos.hora_egreso_consultorio, turnos.falta_registro_ingresos"))
                     ->where('turnos.agenda_id', '=', $this->id)
					 ->orderBy('turnos.hora_desde', 'asc')
                     ->get();
	}


}

