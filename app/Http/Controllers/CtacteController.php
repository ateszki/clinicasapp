<?php

class CtacteController extends MaestroController {

	function __construct(){
		$this->classname= 'Ctacte';
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
		return $this->crear();
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
			return Response::json(array(
			'error'=>true,
			'mensaje' => 'Accion no disponible',
			),200);
/*
		if (Input::has(array('importe_bruto','importe_neto','importe_iva','bonificacion','total'))){
			$importe_neto = Input::get('importe_neto'); 
			$importe_bruto = Input::get('importe_bruto'); 
			$importe_iva = Input::get('importe_iva');
			$bonificacion = Input::get('bonificacion');
			$total = Input::get('total');
			if (!$this->checkImportes($id,$importe_neto,$importe_bruto,$importe_iva,$bonificacion,$total)){
					return Response::json(array(
					'error'=>true,
					'mensaje' => 'No coinciden los importes',
					'listado'=>Input::all(),
					),200);
			}
 
		}
		return parent::update($id);
*/	}

	private function checkImportes($id,$neto,$bruto,$iva,$total,$bonificacion){
		if (($bruto - ($bruto*($bonificacion/10)))!= ($neto+$iva)){
			return false;
		} else {
			$ctacte = Ctacte::where('id','=',$id)->get();
			$lineas = $ctacte->lineas_factura();
			$total_lineas = 0;
			foreach ($lineas as $lin){
				$total_lineas += $lin->importe;
			}
			return ($neto == $total_lineas);
		}	
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		DB::beginTransaction();
		try
		{
			$modelo = $this->modelo->find($id);

			//check de eliminacion
			$salir = false;
			$errorMsg = '';
			if ($modelo->print_ok ){
				$salir = true;
				$error = 'El comprobante ya fue impreso en el controlador fiscal';
			}
			if(!empty($modelo->fecha_transferencia_bas)){
				$salir = true;
				$error = 'El comprobante ya fue transferido al sistema contable';
			}
			if($salir){
			    DB::rollback();
				return Response::json(array(
					'error' => true,
					'listado'=>$modelo->toArray(),
					'mensaje' => $error),
					200
				    );
			}
			DB::enableQueryLog();
			$eliminado = $modelo->delete();
			if ($eliminado === true){
				DB::commit();
				$this->eventoAuditar($modelo);
				return Response::json(array('error'=>false,'listado'=>$modelo->toArray()),200);
			} else {
			    DB::rollback();
				return Response::json(array(
					'error' => true,
					'mensaje' => 'Se produjo un error. No se pudo eliminar el objeto'),
					200
				    );
			}
		} catch(\Exception $e){
			    DB::rollback();
				return Response::json(array(
					'error' => true,
					'mensaje' => $e->getMessage()),
					200
				    );
		}
	}

	public function movimientosPaciente($paciente_id){

		$pacientes_prepagas = PacientePrepaga::where('paciente_id','=',$paciente_id)->with('ctactes')->get()->toArray();
		$movimientos = array();	
		foreach ($pacientes_prepagas as $pp){
		//	var_dump($pp);die();	
				$mov = $pp["ctactes"];
				$prepaga = Prepaga::find($pp["prepaga_id"]);
				foreach($mov as $m){
					$coe = CentroOdontologoEspecialidad::find($m["centro_odontologo_especialidad_id"]);
					$m["odontologo"] = $coe->odontologo->nombre_completo;
					$m["especialidad"] = $coe->especialidad->especialidad;
					$m["prepaga"] = $prepaga->razon_social;
					$m["prepaga_codigo"] = $prepaga->codigo;
				//	$m["saldo"] = 0;
					//$m["fecha"] = substr($m["fecha"],-2)."-".substr($m["fecha"],5,2)."-".substr($m["fecha"],0,4);
					$movimientos[] = $m;
					//$movimientos = array_merge($movimientos,$mov);
				} 
		//		dd($movimientos);
		}
		return Response::json(array(
		'error'=>false,
		'listado'=>$movimientos),
		200);

}

	public function crear(){
		DB::beginTransaction();
		try
		{

		DB::enableQueryLog();
		$data = Input::all();
		$new = $data;
		unset($new['apikey']);
		unset($new['session_key']);

		$items = array();
		$pagos = array();

		$new = array_map(function($n){return ($n == 'NULL')?NULL:$n;}, $new);
		if(isset($new["items"])){	
			foreach ($new["items"] as $i => $item){
				$items[$i] = array_map(function($n){return ($n == 'NULL')?NULL:$n;},$item); 
			}
			unset($new["items"]);
		}
		if(isset($new["pago"])){
			foreach ($new["pago"] as $p => $pago){
				$pagos[$p] = array_map(function($n){return ($n == 'NULL')?NULL:$n;},$pago); 
			}
			unset($new["pago"]);
		}
		$new["user_id"] = Auth::user()->id;
		$modelo_ctacte = new Ctacte();
		$ctacte = $modelo_ctacte->create($new);
		DB::enableQueryLog();
		if ($ctacte->save()){
				$this->eventoAuditar($ctacte);
				if (count($items)){
				foreach($items as $item){
					$ctacte_fac_lin = new CtacteFacLin();
					$item['ctacte_id']=$ctacte->id;
					$fac_lin = $ctacte_fac_lin->create($item);
					if(!$fac_lin->save()){
						DB::rollback();
						return Response::json(array(
						'error'=>true,
						'mensaje' => HerramientasController::getErrores($fac_lin->validator),
						'listado'=>$data,
						'paso'=>1,
						),200);
					
					}
				}
				}
				if(count($pagos)){
				$referencia = $ctacte->id;
				if(substr($ctacte->tipo_prev,0,1) =='F'){
				$new["tipo_cbte"] = (!empty($new["tipo_cbte"]))?'RE':NULL;
				$new["tipo_prev"] = 'RE';
				$new["referencia"] = $ctacte->id;
				$new["importe_iva"] = 0;
				$importe = 0;	
				foreach ($pagos as $p){
					$importe += $p["importe"];
				}
				$new["importe_neto"] = $importe;
				$new["importe_bruto"] = $importe;
				$modelo_ctacte1 = new Ctacte();
				$ctacte1 = $modelo_ctacte1->create($new);
				DB::enableQueryLog();
					if ($ctacte1->save()){
						$this->eventoAuditar($ctacte1);
						$referencia = $ctacte1->id;
					} else {
							DB::rollback();
							return Response::json(array(
							'error'=>true,
							'mensaje' => HerramientasController::getErrores($ctacte1->validator),
							'paso'=>2,
							'listado'=>$data,
							),200);
					
					}					
				}
				foreach($pagos as $pago){
					$ctacte_rec_lin = new CtacteRecLin();
					$pago['ctacte_id']=$referencia;
					$rec_lin = $ctacte_rec_lin->create($pago);
					if(!$rec_lin->save()){
						DB::rollback();
						return Response::json(array(
						'error'=>true,
						'mensaje' => HerramientasController::getErrores($rec_lin->validator),
						'listado'=>$data,
						'pas'=>3,
						),200);
					
					}
				}
					if (!MovimientoCaja::ingresoCtacte($referencia)){
							DB::rollback();
							return Response::json(array(
							'error'=>true,
							'mensaje' => "No se pudo grabar movimiento de caja asociado",
							'listado'=>$data,
							),200);
					}
				}
				DB::commit();
				return Response::json(array(
				'error'=>false,
				//'listado'=>array($ctacte->with('lineas_factura','lineas_recibo')->where('id','=',$ctacte->id)->get()->toArray())),
				'listado'=>$ctacte->where('id','=',$ctacte->id)->get()->toArray()),
				200);
			} else {
				DB::rollback();
				return Response::json(array(
				'error'=>true,
				'mensaje' => HerramientasController::getErrores($ctacte->validator),
				'paso' => 4,
				'listado'=>$data,
				),200);
			}

		

	} catch(\Exception $e)
			{
			    DB::rollback();
				return Response::json(array(
					'error' => true,
					'mensaje' => $e->getMessage()),
					200
				    );
			}
	} 
	
	public function traerMovimiento($id){
		try {
			$mov = Ctacte::findOrFail($id);
				return Response::json(array(
				'error'=>false,
				'listado'=>$mov->toArray()),
				200);

		}catch (Exception $e){
	
			return Response::json(array(
				'error' => true,
				'mensaje' => $e->getMessage()),
				200
				    );
		}

	}
	public function traerItems($id){
		try {
			$items = Ctacte::findOrFail($id)->lineas_factura()->get();
				return Response::json(array(
				'error'=>false,
				'listado'=>$items->toArray()),
				200);

		}catch (Exception $e){
	
			return Response::json(array(
				'error' => true,
				'mensaje' => $e->getMessage()),
				200
				    );
		}

	}
	public function traerPagos($id){
		try {
			$pagos = Ctacte::findOrFail($id)->lineas_recibo()->get();
				return Response::json(array(
				'error'=>false,
				'listado'=>$pagos->toArray()),
				200);

		}catch (Exception $e){
	
			return Response::json(array(
				'error' => true,
				'mensaje' => $e->getMessage()),
				200
				    );
		}

	}
	public function printStatus($id){
		$C = Ctacte::findOrFail($id);
		try {
			$C->print_ok = Input::get('print_ok');
			$C->impresora_fiscal = Input::get('impresora_fiscal');
			if($C->save()){
				return Response::json(array(
				'error'=>false,
				'listado'=>$C->toArray()),
				200);

			} else {
				return Response::json(array(
					'error' => true,
					'mensaje' => HerramientasController::getErrores($C->validator),
					'listado'=>$C->toArray(),
				),200);
			}
		}catch (Exception $e){
	
			return Response::json(array(
				'error' => true,
				'mensaje' => $e->getMessage()),
				200
				    );
		}
	
	}
	public function updateTicket($id){
		$C = Ctacte::findOrFail($id);
		try {
			$C->ticket = Input::get('ticket');
			$C->fecha_ticket = Input::get('fecha_ticket');
			if($C->save()){
				return Response::json(array(
				'error'=>false,
				'listado'=>$C->toArray()),
				200);
			} else {
				return Response::json(array(
					'error' => true,
					'mensaje' => HerramientasController::getErrores($C->validator),
					'listado'=>$C->toArray(),
				),200);
			}

		}catch (Exception $e){
	
			return Response::json(array(
				'error' => true,
				'mensaje' => $e->getMessage()),
				200
				    );
		}
	
	}
	public function movimientosxCajayFecha(){
		$params = Input::all();
		try {
			$a = DB::select("SELECT c.id, c.caja_id, c.fecha, pr.codigo, c.tipo_movimiento, c.tipo_prev, c.ticket,c.referencia, c.importe_bruto, c.importe_neto,
							c.importe_iva, c.importe_total,ca.caja, CONCAT( p.tipo_documento,  ' ', p.nro_documento ) AS documento, 
							t.debehaber,c.user_id, u.nombre as nombre_usuario, u.username,c.prefijo_cbte,c.nro_cbte,c.print_ok 
							FROM ctactes c
							INNER JOIN paciente_prepaga pp ON c.paciente_prepaga_id = pp.id
							INNER JOIN pacientes p ON pp.paciente_id = p.id
							INNER JOIN prepagas pr ON pp.prepaga_id = pr.id
							INNER JOIN cajas ca ON ca.id = c.caja_id
							INNER JOIN tablas t ON t.codigo_tabla='COMPROBANTES_CTACTE' and t.valor =c.tipo_prev
							INNER JOIN users u ON c.user_id = u.id
							WHERE fecha >= ? and fecha <= ?
							ORDER BY c.fecha,c.caja_id,c.id",[$params['fechadesde'],$params['fechahasta']]);
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
	public function movimientosxCajayFechaPagos(){
		$params = Input::all();
		try {
			$a = DB::select("SELECT c.id, c.caja_id, c.fecha, c.tipo_prev, l.tipo, l.descripcion, l.importe,l.numero_cheque,l.numero_cupon,
							l.codigo_tarjeta,l.codigo_plan,l.tipo_cambio,ca.caja,c.prefijo_cbte,c.nro_cbte,c.ticket,
							CONCAT( p.tipo_documento,  ' ', p.nro_documento ) AS documento,c.user_id, u.nombre as nombre_usuario, u.username
							FROM ctactes c
							INNER JOIN ctactes_rec_lin l ON l.ctacte_id = c.id
							INNER JOIN users u ON c.user_id = u.id
							INNER JOIN cajas ca ON ca.id = c.caja_id
							INNER JOIN paciente_prepaga pp ON c.paciente_prepaga_id = pp.id
							INNER JOIN pacientes p ON pp.paciente_id = p.id
							WHERE fecha >= ? and fecha <= ?
							ORDER BY c.fecha,c.caja_id,l.tipo,c.id",[$params['fechadesde'],$params['fechahasta']]);
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
