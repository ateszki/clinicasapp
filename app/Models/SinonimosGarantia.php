<?php

class SinonimosGarantia extends Maestro {

	protected $table = 'sinonimos_garantia'; 	

	protected $fillable = array(
		'nomenclador_id',
		'sinonimo_id',
		);


	public $rules = array(
		'sinonimo_id'=>'Required|integer|exists:nomencladores,id',
		'nomenclador_id'=>'Required|integer|exists:nomencladores,id',
                );

	public function sinonimo(){
		return $this->belongsTo('Nomencladores','sinonimo_id');
	}	


	public function nomenclador(){
		return $this->belongsTo('Nomencladores');
	}	

}
