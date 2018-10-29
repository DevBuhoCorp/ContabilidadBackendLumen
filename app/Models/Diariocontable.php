<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 29 Oct 2018 16:47:59 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Diariocontable
 * 
 * @property int $ID
 * @property string $Codigo
 * @property string $Etiqueta
 * @property int $IDNaturaleza
 * @property string $Estado
 * 
 * @property \App\Models\Naturaleza $naturaleza
 * @property \Illuminate\Database\Eloquent\Collection $cuentacontables
 *
 * @package App\Models
 */
class Diariocontable extends Eloquent
{
	protected $table = 'diariocontable';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $casts = [
		'IDNaturaleza' => 'int'
	];

	protected $fillable = [
		'Codigo',
		'Etiqueta',
		'IDNaturaleza',
		'Estado'
	];

	public function naturaleza()
	{
		return $this->belongsTo(\App\Models\Naturaleza::class, 'IDNaturaleza');
	}

	public function cuentacontables()
	{
		return $this->hasMany(\App\Models\Cuentacontable::class, 'IDDiario');
	}
}
