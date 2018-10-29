<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 29 Oct 2018 16:47:59 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Documentocontable
 * 
 * @property int $ID
 * @property \Carbon\Carbon $Fecha
 * @property string $SerieDocumento
 * @property string $FormaPago
 * @property string $PuntoVenta
 * @property string $Sucursal
 * @property float $Descuento
 * @property float $IVA
 * @property float $Total
 * @property string $Imagen
 * @property string $Tipo
 * @property int $IDTransaccion
 * 
 * @property \App\Models\Transaccion $transaccion
 * @property \Illuminate\Database\Eloquent\Collection $detalledoccontables
 *
 * @package App\Models
 */
class Documentocontable extends Eloquent
{
	protected $table = 'documentocontable';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $casts = [
		'Descuento' => 'float',
		'IVA' => 'float',
		'Total' => 'float',
		'IDTransaccion' => 'int'
	];

	protected $dates = [
		'Fecha'
	];

	protected $fillable = [
		'Fecha',
		'SerieDocumento',
		'FormaPago',
		'PuntoVenta',
		'Sucursal',
		'Descuento',
		'IVA',
		'Total',
		'Imagen',
		'Tipo',
		'IDTransaccion'
	];

	public function transaccion()
	{
		return $this->belongsTo(\App\Models\Transaccion::class, 'IDTransaccion');
	}

	public function detalledoccontables()
	{
		return $this->hasMany(\App\Models\Detalledoccontable::class, 'IDDocContable');
	}
}
