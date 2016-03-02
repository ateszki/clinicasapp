<?php

class InconsistenciasAuditoria extends Maestro {

	protected $table = 'inconsistencias_auditoria'; 	

	protected $fillable = array(
		'nomenclador_id',
		'previo_id',
		);


	public $rules = array(
		'previo_id'=>'Required|integer|exists:nomencladores,id',
		'nomenclador_id'=>'Required|integer|exists:nomencladores,id',
                );

	public function previo(){
		return $this->belongsTo('Nomencladores','previo_id');
	}	


	public function nomenclador(){
		return $this->belongsTo('Nomencladores');
	}	

}
