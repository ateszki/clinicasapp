<?php

class Autorizacion extends Maestro {

	protected $table = 'autorizaciones'; 	

	protected $fillable = array(
		'user_id_autorizante',
		'user_id_autorizado',
		'role_id',
		'hasta',
		'comentario',
		);


	public $rules = array(
			'user_id_autorizante' => 'integer|exists:users,id',
			'user_id_autorizado' => 'required|integer|exists:users,id',
			'role_id' => 'required|integer|exists:roles,id',
			'hasta' => 'date',
			'comentario'=>'max:255',
                );

	public function autorizante(){
		return $this->belongsTo(\User::class,'user_id_autorizante');
	}
	public function autorizado(){
		return $this->belongsTo(\User::class,'user_id_autorizado');
	}
	public function role(){
		return $this->belongsTo(\Role::class);
	}
	
}
