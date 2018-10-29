<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 29 Oct 2018 16:47:59 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Tipobalance
 * 
 * @property int $ID
 * @property string $Etiqueta
 * @property string $Estado
 * 
 * @property \Illuminate\Database\Eloquent\Collection $cuentabalances
 *
 * @package App\Models
 */
class Tipobalance extends Eloquent
{
	protected $table = 'tipobalance';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $fillable = [
		'Etiqueta',
		'Estado'
	];

	public function cuentabalances()
	{
		return $this->hasMany(\App\Models\Cuentabalance::class, 'IDBalance');
	}
}
