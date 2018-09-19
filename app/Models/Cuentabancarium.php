<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 13 Sep 2018 22:19:14 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Cuentabancarium
 * 
 * @property int $ID
 * @property string $Referencia
 * @property string $Cuenta
 * @property string $Etiqueta
 * @property int $TipoCuenta
 * @property string $Estado
 * @property string $Web
 * @property float $SaldoInicial
 * @property \Carbon\Carbon $Fecha
 * @property float $SaldoMinimo
 * @property string $NombreBanco
 * @property string $NumeroCuenta
 * @property string $NombreTitular
 * @property string $DireccionTitular
 * @property string $CuentaBancariacol
 * @property int $IDCuenta
 * 
 * @property \App\Models\Cuentacontable $cuentacontable
 * @property \App\Models\Tipocuentabancarium $tipocuentabancarium
 *
 * @package App\Models
 */
class Cuentabancarium extends Eloquent
{
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $casts = [
		'TipoCuenta' => 'int',
		'SaldoInicial' => 'float',
		'SaldoMinimo' => 'float',
		'IDCuenta' => 'int'
	];

	protected $dates = [
		'Fecha'
	];

	protected $fillable = [
		'Referencia',
		'Cuenta',
		'Etiqueta',
		'TipoCuenta',
		'Estado',
		'Web',
		'SaldoInicial',
		'Fecha',
		'SaldoMinimo',
		'NombreBanco',
		'NumeroCuenta',
		'NombreTitular',
		'DireccionTitular',
		'CuentaBancariacol',
		'IDCuenta'
	];

	public function cuentacontable()
	{
		return $this->belongsTo(\App\Models\Cuentacontable::class, 'IDCuenta');
	}

	public function tipocuentabancarium()
	{
		return $this->belongsTo(\App\Models\Tipocuentabancarium::class, 'TipoCuenta');
	}
}
