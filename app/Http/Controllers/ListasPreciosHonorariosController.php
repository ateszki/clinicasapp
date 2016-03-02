<?php

class ListasPreciosHonorariosController extends MaestroController {

	function __construct(){
		$this->classname= 'ListasPreciosHonorarios';
		$this->modelo = new $this->classname();
	}

	public function valores($lista_id,$fecha=NULL,$nomenclador_id=NULL){
		$valores = [];
		$valoresQuery = DB::table('listas_honorarios_valores')
					->join('nomencladores','listas_honorarios_valores.nomenclador_id','=','nomencladores.id')
					->where('listas_honorarios_valores.lista_id','=',$lista_id)
					->select(DB::raw('listas_honorarios_valores.id,listas_honorarios_valores.valor,listas_honorarios_valores.vigencia,nomencladores.id as nomenclador_id,nomencladores.codigo,nomencladores.descripcion'));
		$vigencia = DB::select("SELECT max( vigencia ) as vigencia
			FROM `listas_honorarios_valores`
			WHERE lista_id = ?",[$lista_id]);
		if ($fecha == NULL && $nomenclador_id == NULL){
			$valores = $valoresQuery->where('vigencia','=',$vigencia[0]->vigencia)->get();
		}

		if(!strpos($fecha,"-") && $fecha != NULL && $nomenclador_id == NULL){
			$nomenclador_id = $fecha;
			$fecha = NULL;
			$valores = $valoresQuery->where('vigencia','=',$vigencia[0]->vigencia)->where('listas_honorarios_valores.nomenclador_id','=',$nomenclador_id)->get();
		}
		if(strpos($fecha,"-") && $fecha != NULL){
			$vigencia = DB::select("SELECT max( vigencia ) as vigencia
				FROM `listas_honorarios_valores`
				WHERE lista_id = ?
				AND vigencia <= ?",[$lista_id,$fecha]);
			if($nomenclador_id == NULL){
				$valores = $valoresQuery->where('listas_honorarios_valores.vigencia','=',$vigencia[0]->vigencia)->get();
			}else{
				$valores = $valoresQuery->where('listas_honorarios_valores.vigencia','=',$vigencia[0]->vigencia)->where('listas_honorarios_valores.nomenclador_id','=',$nomenclador_id)->get();
			}
		}
		return Response::json(array(
			'error' => false,
			'listado' => $valores),
			200
		);	

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

}
