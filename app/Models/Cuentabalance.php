<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 29 Oct 2018 16:47:59 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Cuentabalance
 * 
 * @property int $ID
 * @property int $IDPlanContable
 * @property int $IDBalance
 * @property string $Estado
 * 
 * @property \App\Models\Plancontable $plancontable
 * @property \App\Models\Tipobalance $tipobalance
 *
 * @package App\Models
 */
class Cuentabalance extends Eloquent
{
	protected $table = 'cuentabalance';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $casts = [
		'IDPlanContable' => 'int',
		'IDBalance' => 'int'
	];

	protected $fillable = [
		'IDPlanContable',
		'IDBalance',
		'Estado'
	];

	public function plancontable()
	{
		return $this->belongsTo(\App\Models\Plancontable::class, 'IDPlanContable');
	}

	public function tipobalance()
	{
		return $this->belongsTo(\App\Models\Tipobalance::class, 'IDBalance');
	}
}
