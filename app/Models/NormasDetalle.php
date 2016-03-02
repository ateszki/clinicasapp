<?php

class NormasDetalle extends Maestro {

	protected $table = 'normas_detalle'; 	

	protected $fillable = array(
		'normas_auditoria_id',
		'reglas_auditoria_id',
		'nomenclador_id',
		'limite_edad',
		'edad_desde',
		'edad_hasta',
		'periodo_garantia',
		'meses_garantia',
		'cantidad_sesion',
		'requiere_odontograma',
		'cantidad_rx',
		'nivel_garantia',
		'paga_rx',
		);


	public $rules = array(
		'normas_auditoria_id'=>'Required|integer|exists:normas_auditoria,id',
		'reglas_auditoria_id'=>'Required|integer|exists:reglas_auditoria,id',
		'nomenclador_id'=>'Required|integer|exists:nomencladores,id',
		'limite_edad'=>'Required|boolean',
		'edad_desde'=>'integer',
		'edad_hasta'=>'integer',
		'periodo_garantia'=>'Required|boolean',
		'meses_garantia'=>'integer',
		'cantidad_sesion'=>'required|integer',
		'requiere_odontograma'=>'require|boolean',
		'cantidad_rx'=>'required|integer',
		'nivel_garantia'=>'max:1|in:M,P,C',
		'paga_rx'=>'required|boolean',
                );

	public function norma(){
		return $this->belongsTo('NormasAuditoria','normas_auditoria_id');
	}	

	public function regla(){
		return $this->belongsTo('ReglasAuditoria','reglas_auditoria_id');
	}	

	public function nomenclador(){
		return $this->belongsTo('Nomencladores');
	}	

}
