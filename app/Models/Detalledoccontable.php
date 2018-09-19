<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 13 Sep 2018 22:19:14 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Detalledoccontable
 * 
 * @property int $ID
 * @property int $IDDocContable
 * @property string $CodigoProducto
 * @property string $Descripcion
 * @property float $ValorUnitario
 * @property float $Cantidad
 * @property float $Total
 * 
 * @property \App\Models\Documentocontable $documentocontable
 *
 * @package App\Models
 */
class Detalledoccontable extends Eloquent
{
	protected $table = 'detalledoccontable';
	protected $primaryKey = 'ID';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'ID' => 'int',
		'IDDocContable' => 'int',
		'ValorUnitario' => 'float',
		'Cantidad' => 'float',
		'Total' => 'float'
	];

	protected $fillable = [
		'IDDocContable',
		'CodigoProducto',
		'Descripcion',
		'ValorUnitario',
		'Cantidad',
		'Total'
	];

	public function documentocontable()
	{
		return $this->belongsTo(\App\Models\Documentocontable::class, 'IDDocContable');
	}
}
