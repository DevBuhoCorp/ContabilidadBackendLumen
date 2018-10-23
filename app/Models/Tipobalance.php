<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 23 Oct 2018 15:13:18 +0000.
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
