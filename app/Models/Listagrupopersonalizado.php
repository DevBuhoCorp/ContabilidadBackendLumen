<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 29 Oct 2018 16:47:59 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Listagrupopersonalizado
 * 
 * @property int $ID
 * @property int $IDGrupo
 * @property int $IDCuenta
 * 
 * @property \App\Models\Cuentacontable $cuentacontable
 * @property \App\Models\Grupopersonalizado $grupopersonalizado
 *
 * @package App\Models
 */
class Listagrupopersonalizado extends Eloquent
{
	protected $table = 'listagrupopersonalizado';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $casts = [
		'IDGrupo' => 'int',
		'IDCuenta' => 'int'
	];

	protected $fillable = [
		'IDGrupo',
		'IDCuenta'
	];

	public function cuentacontable()
	{
		return $this->belongsTo(\App\Models\Cuentacontable::class, 'IDCuenta');
	}

	public function grupopersonalizado()
	{
		return $this->belongsTo(\App\Models\Grupopersonalizado::class, 'IDGrupo');
	}
}
