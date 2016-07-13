<?php

class FichadoItem extends Maestro {

	protected $table = 'fichados_items'; 	

	protected $appends = array('numero_pieza_dental','descripcion_referencia','requiere_pieza','requiere_cara','requiere_sector','multiples_piezas','imagen_odontograma_anterior','imagen_odontograma_arealizar');
	
	protected $fillable = array(
			'fichado_id',
			'referencia_fichado_id',
			'tipo_referencia',
			'pieza_dental_id',
			'caras',
	);


	public $rules = array(
			'fichado_id' => 'required|integer|exists:fichados,id',
			'referencia_fichado_id' => 'required|integer|exists:referencias_fichados,id',
			'tipo_referencia'=>'required|max:1',
			'pieza_dental_id' => 'integer|exists:piezas_dentales,id',
			'caras'=>'max:5',
                );

	

	public function fichado(){
		return $this->belongsTo('Fichado');
	}
	public function referencia_fichado(){
		return $this->belongsTo('ReferenciaFichado');
	}
	public function pieza_dental(){
		return $this->belongsTo('PiezaDental');
	}
	
	public function getNumeroPiezaDentalAttribute($value){
		return (empty($this->pieza_dental_id))?NULL:$this->pieza_dental()->first()->diente;
	}

	public function getDescripcionReferenciaAttribute($value){
		return (empty($this->referencia_fichado_id))?NULL:$this->referencia_fichado()->first()->descripcion;
	}

	public function getRequierePiezaAttribute($value){
		return (empty($this->referencia_fichado_id))?NULL:$this->referencia_fichado()->first()->requiere_pieza;
	}
	public function getRequiereCaraAttribute($value){
		return (empty($this->referencia_fichado_id))?NULL:$this->referencia_fichado()->first()->requiere_cara;
	}
	public function getRequiereSectorAttribute($value){
		return (empty($this->referencia_fichado_id))?NULL:$this->referencia_fichado()->first()->requiere_sector;
	}
	public function getMultiplesPiezasAttribute($value){
		return (empty($this->referencia_fichado_id))?NULL:$this->referencia_fichado()->first()->multiples_piezas;
	}
	public function getImagenOdontogramaAnteriorAttribute($value){
		return (empty($this->referencia_fichado_id))?NULL:$this->referencia_fichado()->first()->imagen_odontograma_anterior;
	}
	public function getImagenOdontogramaArealizarAttribute($value){
		return (empty($this->referencia_fichado_id))?NULL:$this->referencia_fichado()->first()->imagen_odontograma_arealizar;
	}
	public function getEsquema(){
		$esquema = parent::getEsquema();
		$esquema[] = array(
			      "Field"=> "descripcion_referencia",
				"Type"=> "varchar(100)",
			      "Null"=> "YES",
			      "Key"=> "",
			      "Default"=> null,
			      "Extra"=> ""
		);
		$esquema[] = array(
			      "Field"=> "numero_pieza_dental",
				"Type"=> "varchar(2)",
			      "Null"=> "YES",
			      "Key"=> "",
			      "Default"=> null,
			      "Extra"=> ""
		);
		$esquema[] = array(
			      "Field"=> "requiere_pieza",
				"Type"=> "tinyint(1)",
			      "Null"=> "YES",
			      "Key"=> "",
			      "Default"=> null,
			      "Extra"=> ""
		);
		$esquema[] = array(
			      "Field"=> "requiere_cara",
				"Type"=> "tinyint(1)",
			      "Null"=> "YES",
			      "Key"=> "",
			      "Default"=> null,
			      "Extra"=> ""
		);
		$esquema[] = array(
			      "Field"=> "requiere_sector",
				"Type"=> "tinyint(1)",
			      "Null"=> "YES",
			      "Key"=> "",
			      "Default"=> null,
			      "Extra"=> ""
		);
		$esquema[] = array(
			      "Field"=> "multiples_piezas",
				"Type"=> "tinyint(1)",
			      "Null"=> "YES",
			      "Key"=> "",
			      "Default"=> null,
			      "Extra"=> ""
		);
		$esquema[] = array(
			      "Field"=> "imagen_odontograma_anterior",
				"Type"=> "varchar(25)",
			      "Null"=> "YES",
			      "Key"=> "",
			      "Default"=> null,
			      "Extra"=> ""
		);
		$esquema[] = array(
			      "Field"=> "imagen_odontograma_arealizar",
				"Type"=> "varchar(25)",
			      "Null"=> "YES",
			      "Key"=> "",
			      "Default"=> null,
			      "Extra"=> ""
		);
		return $esquema;
	}
	
}
