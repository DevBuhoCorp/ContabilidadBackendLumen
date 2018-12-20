<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 29 Oct 2018 16:47:59 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Transaccion
 * 
 * @property int $ID
 * @property \Carbon\Carbon $Fecha
 * @property int $IDEstacion
 * @property int $IDEmpresa
 * @property int $IDUser
 * @property string $Etiqueta
 * @property float $Debe
 * @property float $Haber
 * @property string $Estado
 * 
 * @property \App\Models\Estacion $estacion
 * @property \Illuminate\Database\Eloquent\Collection $detalletransaccions
 * @property \Illuminate\Database\Eloquent\Collection $documentocontables
 *
 * @package App\Models
 */
class Transaccion extends Eloquent
{
	protected $table = 'transaccion';
	protected $primaryKey = 'ID';
//	public $timestamps = false;

	protected $casts = [
		'IDEstacion' => 'int',
		'IDEmpresa' => 'int',
		'IDUser' => 'int',
		'Debe' => 'float',
		'Haber' => 'float'
	];

	protected $dates = [
		'Fecha'
	];

	protected $fillable = [
		'Fecha',
		'IDEstacion',
		'IDEmpresa',
		'IDUser',
		'Etiqueta',
		'Debe',
		'Haber',
		'Estado'
	];

	public function estacion()
	{
		return $this->belongsTo(\App\Models\Estacion::class, 'IDEstacion');
	}

	public function detalletransaccions()
	{
		return $this->hasMany(\App\Models\Detalletransaccion::class, 'IDTransaccion');
	}

	public function detalletransaccions_v2()
	{
		return $this->hasMany(\App\Models\Detalletransaccion::class, 'IDTransaccion')
            ->select('DetalleTransaccion.ID', 'DetalleTransaccion.IDTransaccion', 'CuentaContable.NumeroCuenta', 'CuentaContable.Etiqueta', 'DetalleTransaccion.Debe', 'DetalleTransaccion.Haber' )
            ->join('PlanContable', 'PlanContable.ID', 'DetalleTransaccion.IDCuenta' )
            ->join('CuentaContable', 'CuentaContable.ID',  'PlanContable.IDCuenta');
//            ->get();
	}

	public function documentocontables()
	{
		return $this->hasMany(\App\Models\Documentocontable::class, 'IDTransaccion');
	}
}
