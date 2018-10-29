<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 29 Oct 2018 16:47:59 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Grupocuentum
 * 
 * @property int $ID
 * @property string $Etiqueta
 * 
 * @property \Illuminate\Database\Eloquent\Collection $cuentacontables
 *
 * @package App\Models
 */
class Grupocuentum extends Eloquent
{
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $fillable = [
		'Etiqueta'
	];

	public function cuentacontables()
	{
		return $this->hasMany(\App\Models\Cuentacontable::class, 'IDGrupoCuenta');
	}
}
