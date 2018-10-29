<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 29 Oct 2018 16:47:59 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Cuentaimpuesto
 * 
 * @property int $ID
 * @property string $Codigo
 * @property string $Etiqueta
 * @property int $IDCuenta
 * @property string $Estado
 * 
 * @property \App\Models\Cuentacontable $cuentacontable
 *
 * @package App\Models
 */
class Cuentaimpuesto extends Eloquent
{
	protected $table = 'cuentaimpuesto';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $casts = [
		'IDCuenta' => 'int'
	];

	protected $fillable = [
		'Codigo',
		'Etiqueta',
		'IDCuenta',
		'Estado'
	];

	public function cuentacontable()
	{
		return $this->belongsTo(\App\Models\Cuentacontable::class, 'IDCuenta');
	}
}
