<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 29 Oct 2018 16:47:59 +0000.
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
	public $timestamps = false;

	protected $casts = [
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
