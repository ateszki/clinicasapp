<?php

class TratamientoController extends MaestroController {

	function __construct(){
		$this->classname= 'Tratamiento';
		$this->modelo = new $this->classname();
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		return parent::index();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		return parent::store();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return parent::show($id);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		return parent::update($id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		return parent::destroy($id);
	}
	public function tratamientosEntreFechas(){
	
		try {
			$params = Input::all();
			$a = DB::select("SELECT a.id as agenda_id, a.fecha, coe.centro_id, c.razonsocial AS centro,c.identificador, coe.especialidad_id, e.especialidad, coe.odontologo_id, concat( o.apellido, ', ', o.nombres ) AS odontologo, coe.turno as horario,t.hora_desde,t.paciente_prepaga_id as paciente_pre,pp.paciente_id as paciente,t.id as turno,if(t.paciente_prepaga_id is not null,1,0) as asignado,if(tr.nomenclador_id is not null,1,0) as presente,tr.nomenclador_id,n.descripcion,n.codigo,left(n.codigo,2) as capitulo,if(tr.nomenclador_id is not null,1,0) as cantidad,if(tr.nomenclador_id is not null,tr.valor,0) as valor
			FROM agendas a
			INNER JOIN centros_odontologos_especialidades coe ON a.centro_odontologo_especialidad_id = coe.id
			INNER JOIN odontologos o ON coe.odontologo_id = o.id
			INNER JOIN especialidades e ON coe.especialidad_id = e.id
			INNER JOIN centros c ON coe.centro_id = c.id
			INNER JOIN turnos t ON a.id = t.agenda_id 
			LEFT JOIN tratamientos tr ON tr.turno_id = t.id 
			LEFT JOIN nomencladores n ON tr.nomenclador_id = n.id 
			LEFT JOIN paciente_prepaga pp on t.paciente_prepaga_id = pp.id 
			WHERE a.fecha >= ? and a.fecha <= ?", [$params["fecha-desde"], $params["fecha-hasta"]]);
			return Response::json(array(
				'error' => false,
				'listado' => $a),
					200
				    );
			} catch (Exception $e){
						return Response::json(array(
						'error' => true,
						'mensaje' => $e->getMessage()),
						200
					    );
			}
	
	}
	public function tratamientosEntreFechasTotales(){
	
		try {
			$params = Input::all();
			$a = DB::select("SELECT  sum(if(tr.nomenclador_id is not null,1,0)) as prestaciones,sum(tr.valor) as honorarios,
     COUNT(DISTINCT CASE WHEN tr.nomenclador_id is not null THEN p.id END) as pacientes,count(DISTINCT t.id) as turnos
                    FROM agendas a
			INNER JOIN turnos t ON a.id = t.agenda_id 
			LEFT JOIN tratamientos tr ON tr.turno_id = t.id 
			LEFT JOIN paciente_prepaga pp on t.paciente_prepaga_id = pp.id 
			LEFT JOIN pacientes p on pp.paciente_id = p.id 
			WHERE t.paciente_prepaga_id is not null and a.fecha >= ? and a.fecha <= ?", [$params["fecha-desde"], $params["fecha-hasta"]]);
			return Response::json(array(
				'error' => false,
				'listado' => $a),
					200
				    );
			} catch (Exception $e){
						return Response::json(array(
						'error' => true,
						'mensaje' => $e->getMessage()),
						200
					    );
			}
	
	
}
}