<?php
class ListasPreciosHonorarios extends Maestro {

		protected $table = 'listas_precios_honorarios'; 	

		protected $fillable = array(
					'nombre',
					'observaciones',
				);
		public $rules = array(
					'nombre' => 'required',
				);

	public function valores(){
		return $this->hasMany('ListasPreciosHonorariosValores');
	}
}
