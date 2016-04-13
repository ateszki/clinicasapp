<?php
use Carbon\Carbon;

class AutorizacionController extends MaestroController {

	function __construct(){
		$this->classname= 'Autorizacion';
		$this->modelo = new $this->classname();
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		return Response::json(array(
			'error'=>true,
			'mensaje' => 'Accion no disponible',
		),200);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return Response::json(array(
			'error'=>true,
			'mensaje' => 'Accion no disponible',
		),200);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		Input::merge(['user_id_autorizado'=>Auth::user()->id]);
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
		return Response::json(array(
			'error'=>true,
			'mensaje' => 'Accion no disponible',
		),200);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return Response::json(array(
			'error'=>true,
			'mensaje' => 'Accion no disponible',
		),200);
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
	
	public function autorizar($id){
		$a = Autorizacion::findOrFail($id);
		try {
			$a->hasta = Input::get('hasta');
			$a->user_id_autorizante = Auth::user()->id;
			$a->save();
			$this->eventoAuditar($a);
			return Response::json(array(
				'error'=>false,
				'listado'=>$a->toArray()),
			200);
		} catch(\Exception $e){
			return Response::json(array(
				'error' => true,
				'mensaje' => $e->getMessage()),
			200
			);
		}
	}
	public function pendientes(){
		try {
		/**
			$a = Autorizacion::whereRaw('date(created_at) = ?', [Carbon::now()->format('Y-m-d')])->whereNull('user_id_autorizante')->with(['autorizado','role'])->get();
			*/
			$a = DB::select("SELECT a.id,a.user_id_autorizado,a.comentario,a.role_id,r.role,u.nombre FROM `autorizaciones` a
				inner join roles r on a.role_ID=r.ID
				inner join users u on a.user_id_autorizado=u.ID
				WHERE a.user_id_autorizante is NULL and date( a.created_at) = date(curdate())");
			return Response::json(array(
				'error'=>false,
				'listado' => $a),
			200);
		} catch(\Exception $e){
			return Response::json(array(
				'error' => true,
				'mensaje' => $e->getMessage()),
			200
			);
		}

	
	}
}
