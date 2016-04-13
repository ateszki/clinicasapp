<?php

class HerramientasController extends \BaseController {

	public static function getEsquema($modelo){
		$classname = $modelo;
		$m = new $classname();
			 return Response::json(array(
                        'error'=>false,
			'esquema'=>$m->getEsquema(),
                        ),200);

	}

	public static function getErrores($validator){
			$messages = $validator->messages()->toArray();
			$errormessages = array("error"=>"");
			foreach ($messages as $v){
				if(is_array($v)){$errormessages["error"] .= " | ".implode(',',$v);} else {$errormessages["error"] .= " | ".$v;}
			}
			if(strlen($errormessages["error"])){$errormessages["error"] = substr($errormessages["error"],3);}
                        return array($errormessages);
	}
	
	public static function getFechaHora(){

		$mytime = Carbon\Carbon::now();
		$f = $mytime->format("d/m/Y");
		$h = $mytime->format("H:i");
		 return Response::json(array(
                       'error'=>false,
			'listado'=>array("fecha"=>$f,"hora"=>$h),
                  ),200);
		
	} 
}
