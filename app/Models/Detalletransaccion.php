<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 29 Oct 2018 16:47:59 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Detalletransaccion
 * 
 * @property int $ID
 * @property int $IDCuenta
 * @property string $Etiqueta
 * @property float $Debe
 * @property float $Haber
 * @property int $IDTransaccion
 * 
 * @property \App\Models\Plancontable $plancontable
 * @property \App\Models\Transaccion $transaccion
 *
 * @package App\Models
 */
class Detalletransaccion extends Eloquent
{
	protected $table = 'detalletransaccion';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $casts = [
		'IDCuenta' => 'int',
		'Debe' => 'float',
		'Haber' => 'float',
		'IDTransaccion' => 'int'
	];

	protected $fillable = [
		'IDCuenta',
		'Etiqueta',
		'Debe',
		'Haber',
		'IDTransaccion'
	];

	public function plancontable()
	{
		return $this->belongsTo(\App\Models\Plancontable::class, 'IDCuenta');
	}

	public function transaccion()
	{
		return $this->belongsTo(\App\Models\Transaccion::class, 'IDTransaccion');
	}
}
