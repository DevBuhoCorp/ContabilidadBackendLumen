<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 13 Sep 2018 22:19:14 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Cuentacontable
 * 
 * @property int $ID
 * @property string $NumeroCuenta
 * @property string $Etiqueta
 * @property int $IDGrupoCuenta
 * @property int $IDPadre
 * @property string $Estado
 * @property float $Saldo
 * @property int $IDDiario
 * 
 * @property \App\Models\Cuentacontable $cuentacontable
 * @property \App\Models\Diariocontable $diariocontable
 * @property \App\Models\Grupocuentum $grupocuentum
 * @property \Illuminate\Database\Eloquent\Collection $cuentabancaria
 * @property \Illuminate\Database\Eloquent\Collection $cuentacontables
 * @property \Illuminate\Database\Eloquent\Collection $cuentadefectos
 * @property \Illuminate\Database\Eloquent\Collection $cuentaimpuestos
 * @property \Illuminate\Database\Eloquent\Collection $detalletransaccions
 * @property \Illuminate\Database\Eloquent\Collection $listagrupopersonalizados
 * @property \Illuminate\Database\Eloquent\Collection $plancontables
 *
 * @package App\Models
 */
class Cuentacontable extends Eloquent
{
	protected $table = 'cuentacontable';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $casts = [
		'IDGrupoCuenta' => 'int',
		'IDPadre' => 'int',
		'Saldo' => 'float',
		'IDDiario' => 'int'
	];

	protected $fillable = [
		'NumeroCuenta',
		'Etiqueta',
		'IDGrupoCuenta',
		'IDPadre',
		'Estado',
		'Saldo',
		'IDDiario'
	];

	public function cuentacontable()
	{
		return $this->belongsTo(\App\Models\Cuentacontable::class, 'IDPadre');
	}

	public function diariocontable()
	{
		return $this->belongsTo(\App\Models\Diariocontable::class, 'IDDiario');
	}

	public function grupocuentum()
	{
		return $this->belongsTo(\App\Models\Grupocuentum::class, 'IDGrupoCuenta');
	}

	public function cuentabancaria()
	{
		return $this->hasMany(\App\Models\Cuentabancarium::class, 'IDCuenta');
	}

	public function cuentacontables()
	{
		return $this->hasMany(\App\Models\Cuentacontable::class, 'IDPadre');
	}

	public function cuentadefectos()
	{
		return $this->hasMany(\App\Models\Cuentadefecto::class, 'IDCuenta');
	}

	public function cuentaimpuestos()
	{
		return $this->hasMany(\App\Models\Cuentaimpuesto::class, 'IDCuenta');
	}

	public function detalletransaccions()
	{
		return $this->hasMany(\App\Models\Detalletransaccion::class, 'IDCuenta');
	}

	public function listagrupopersonalizados()
	{
		return $this->hasMany(\App\Models\Listagrupopersonalizado::class, 'IDCuenta');
	}

	public function plancontables()
	{
		return $this->hasMany(\App\Models\Plancontable::class, 'IDCuenta');
	}
}
