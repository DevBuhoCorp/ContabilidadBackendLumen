<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 13 Sep 2018 22:19:14 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Transaccion
 *  
 * @property int $ID
 * @property \Carbon\Carbon $Fecha
 * @property int $IDEstacion
 * 
 * @property \App\Models\Estacion $estacion
 * @property \Illuminate\Database\Eloquent\Collection $detalletransaccions
 * @property \Illuminate\Database\Eloquent\Collection $documentocontables
 *
 * @package App\Models
 */
class Transaccion extends Eloquent
{
	protected $table = 'transaccion';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $casts = [
		'IDEstacion' => 'int'
	];

	protected $dates = [
		'Fecha'
	];

	protected $fillable = [
		'Fecha',
		'IDEstacion',
		'IDEmpresa'
	];

	public function estacion()
	{
		return $this->belongsTo(\App\Models\Estacion::class, 'IDEstacion');
	}

	public function detalletransaccions()
	{
		return $this->hasMany(\App\Models\Detalletransaccion::class, 'IDTransaccion');
	}

	public function documentocontables()
	{
		return $this->hasMany(\App\Models\Documentocontable::class, 'IDTransaccion');
	}
}
