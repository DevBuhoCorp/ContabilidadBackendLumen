<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 18 Oct 2018 19:50:53 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Tipoestado
 * 
 * @property int $ID
 * @property string $Etiqueta
 * @property string $Estado
 * 
 * @property \Illuminate\Database\Eloquent\Collection $cuentacontables
 *
 * @package App\Models
 */
class Tipoestado extends Eloquent
{
	protected $table = 'tipoestado';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $fillable = [
		'Etiqueta',
		'Estado'
	];

	public function cuentacontables()
	{
		return $this->hasMany(\App\Models\Cuentacontable::class, 'IDTipoEstado');
	}
}
