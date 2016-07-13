<?php

class PacientePrepaga extends Maestro {

	protected $table = 'paciente_prepaga'; 	

	protected $fillable = array(
		'paciente_id',
		'prepaga_id',
		'numero_credencial',
		'planes_prepaga_id',
		'fecha_alta',
		'fecha_baja',
		'gravado',
		);


	public $rules = array(
                        'paciente_id' => 'Required|integer|exists:pacientes,id',
                        'prepaga_id' => 'Required|integer|exists:prepagas,id',
                        'numero_credencial' => 'Required|max:30',
			'planes_prepaga_id' => 'Required|integer|exists:planes_prepaga,id',
			'fecha_alta' => 'Required|date',
			'fecha_baja' => 'date',
			'gravado' => 'required|boolean',
                );

	public function paciente(){
		return $this->belongsTo('Paciente');
	} 
	public function prepaga(){
		return $this->belongsTo('Prepaga');
	}

	public function ctactes(){
		return $this->hasMany('Ctacte');
	}
	public function vistaDetallada($paciente_id){
		return DB::table('paciente_prepaga')
                     ->join('pacientes','paciente_prepaga.paciente_id','=','pacientes.id')
		     ->join('prepagas','paciente_prepaga.prepaga_id','=','prepagas.id')
		->join('planes_prepaga','paciente_prepaga.planes_prepaga_id','=','planes_prepaga.id')
			->select(DB::raw("paciente_prepaga.id,paciente_prepaga.planes_prepaga_id,paciente_prepaga.
			prepaga_id,paciente_prepaga.paciente_id,paciente_prepaga.numero_credencial,
			planes_prepaga.codigo as plan_cobertura,DATE_FORMAT(paciente_prepaga.fecha_baja,'%d/%m/%Y') AS fecha_baja,
			DATE_FORMAT(paciente_prepaga.fecha_alta,'%d/%m/%Y') AS fecha_alta,concat(pacientes.nombres, ' ',pacientes.apellido) as pacientes,
			concat(pacientes.tipo_documento,' ',pacientes.nro_documento) as documento, prepagas.codigo,prepagas.razon_social,
			prepagas.denominacion_comercial,DATE_FORMAT(prepagas.fecha_alta,'%d/%m/%Y') AS alta_prepaga,DATE_FORMAT(prepagas.fecha_baja,'%d/%m/%Y') AS baja_prepaga,
			prepagas.credencial_propia,prepagas.presenta_padron,paciente_prepaga.gravado"))
            ->where('pacientes.id','=',$paciente_id)
			->get();
	}
	public function turnos(){
		return $this->hasMany('Turno');
	}
	
	public function facturasConSaldo(){
		$saldo= DB::select("SELECT sum(case  tipo_cbte when 'FB' then importe_total else 0 end) - sum(case  tipo_cbte when 'RE' then importe_total else 0 end) as dif  FROM `ctactes` where `paciente_prepaga_id` = ? group by `paciente_prepaga_id`",[$this->id]);
		return (count($saldo))?($saldo[0]->dif != 0):false;
	}

	public function presupuestosConSaldo(){
		$saldo = DB::select("SELECT sum(p.importe_neto) - (select sum(c.importe) as importe from  `ctactes_fac_lin` c where c.presupuesto_id = p.id group by c.presupuesto_id  ) as dif FROM `presupuestos` p WHERE p.`fecha_aprobacion` is not null and p.`paciente_prepaga_id` = ? group by  p.`paciente_prepaga_id`",[$this->id]);
		return (count($saldo))?($saldo[0]->dif != 0):false;
	}
}
