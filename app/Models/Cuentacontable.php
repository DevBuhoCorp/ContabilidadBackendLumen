<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 29 Oct 2018 16:47:59 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

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
 * @property int $IDTipoEstado
 * 
 * @property \App\Models\Tipoestado $tipoestado
 * @property \App\Models\Cuentacontable $cuentacontable
 * @property \App\Models\Diariocontable $diariocontable
 * @property \App\Models\Grupocuentum $grupocuentum
 * @property \Illuminate\Database\Eloquent\Collection $cuentacontables
 * @property \Illuminate\Database\Eloquent\Collection $cuentadefectos
 * @property \Illuminate\Database\Eloquent\Collection $cuentaimpuestos
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
		'IDDiario' => 'int',
		'IDTipoEstado' => 'int'
	];

	protected $fillable = [
		'NumeroCuenta',
		'Etiqueta',
		'IDGrupoCuenta',
		'IDPadre',
		'Estado',
		'Saldo',
		'IDDiario',
		'IDTipoEstado'
	];

    public static function boot() {

        parent::boot();

        static::created(function($item) {
            Event::fire('item.created', $item);
        });



        static::updated(function($item) {

            Event::fire('item.updated', $item);

        });



        static::deleted(function($item) {

            Event::fire('item.deleted', $item);

        });

    }

	public function tipoestado()
	{
		return $this->belongsTo(\App\Models\Tipoestado::class, 'IDTipoEstado');
	}

	public function cuentacontable()
	{
		return $this->belongsTo(\App\Models\Cuentacontable::class, 'IDPadre');
//        return $this->belongsToMany(\App\Models\Cuentacontable::class, 'IDPadre');
	}

	public function diariocontable()
	{
		return $this->belongsTo(\App\Models\Diariocontable::class, 'IDDiario');
	}

	public function grupocuentum()
	{
		return $this->belongsTo(\App\Models\Grupocuentum::class, 'IDGrupoCuenta');
	}

	public function cuentacontables()
	{
		return $this->hasMany(\App\Models\Cuentacontable::class, 'IDPadre');
	}

	#region Recursive
    public function children()
    {
        return $this->hasMany(\App\Models\Cuentacontable::class, 'IDPadre');
    }

	public function allChildren()
	{
        return $this->children()->with('allChildren');
	}
	#endregion

    #region RecursiveBelongTo
    public function childrenBelong()
    {
        return $this->belongsTo(\App\Models\Cuentacontable::class, 'IDPadre');
    }

    public function allChildrenBelong()
    {
        return $this->childrenBelong()->with('allChildrenBelong');
    }
    #endregion


	public function cuentadefectos()
	{
		return $this->hasMany(\App\Models\Cuentadefecto::class, 'IDCuenta');
	}

	public function cuentaimpuestos()
	{
		return $this->hasMany(\App\Models\Cuentaimpuesto::class, 'IDCuenta');
	}

	public function listagrupopersonalizados()
	{
		return $this->hasMany(\App\Models\Listagrupopersonalizado::class, 'IDCuenta');
	}

	public function plancontables()
	{
		return $this->hasMany(\App\Models\Plancontable::class, 'IDCuenta');
	}

//    protected static function boot()
//    {
//        static::updated(function ($product) {
//
//            parent::boot();
//
//            static::created(function($item) {
//
//                Event::fire('item.created', $item);
//
//            });
//
//            static::updated(function($item) {
//
//                Event::fire('item.updated', $item);
//
//            });
//
//
//
//            static::deleted(function($item) {
//
//                Event::fire('item.deleted', $item);
//
//            });
//
//        });
//    }


}
