<?php
class ListasPreciosHonorariosValores extends Maestro {
	protected $table = 'listas_precios_honorarios_valores';
	protected $fillable = array(
		'lista_id',
		'nomeclador_id',
		'valor',
		'vigencia',
	);
	public $rules = array(
		'lista_id' => 'required|exists:listas_precios_honorarios',
		'nomenclador_id' => 'required|exists:nomencladores',
		'valor'=>'requires|numeric',
		'vigencia'=>'required|date',
	);
	public function lista(){
		return $this->belongsTo('ListasPreciosHonorarios');
	}
	public function nomenclador(){
		return $thisNomencladorr');
	}
}
