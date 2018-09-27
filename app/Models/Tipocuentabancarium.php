<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 13 Sep 2018 22:19:14 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Tipocuentabancarium
 * 
 * @property int $ID
 * @property string $Descripcion
 * @property string $Observacion
 *
 * @property \Illuminate\Database\Eloquent\Collection $cuentabancaria
 *
 * @package App\Models
 */
class Tipocuentabancarium extends Eloquent
{
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $fillable = [
		'Descripcion', 'Observacion'
	];

	public function cuentabancaria()
	{
		return $this->hasMany(\App\Models\Cuentabancarium::class, 'TipoCuenta');
	}
}
