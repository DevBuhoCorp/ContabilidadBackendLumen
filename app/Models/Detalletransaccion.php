<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 25 Sep 2018 16:44:25 +0000.
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
 * @property \App\Models\Transaccion $transaccion
 * @property \App\Models\Plancontable $plancontable
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

	public function transaccion()
	{
		return $this->belongsTo(\App\Models\Transaccion::class, 'IDTransaccion');
	}

	public function plancontable()
	{
		return $this->hasOne(\App\Models\Plancontable::class, 'ID', 'IDCuenta');
	}
}
