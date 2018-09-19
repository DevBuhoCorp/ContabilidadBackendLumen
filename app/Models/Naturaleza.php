<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 13 Sep 2018 22:19:14 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Naturaleza
 * 
 * @property int $ID
 * @property string $Etiqueta
 * 
 * @property \Illuminate\Database\Eloquent\Collection $diariocontables
 *
 * @package App\Models
 */
class Naturaleza extends Eloquent
{
	protected $table = 'naturaleza';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $fillable = [
		'Etiqueta'
	];

	public function diariocontables()
	{
		return $this->hasMany(\App\Models\Diariocontable::class, 'IDNaturaleza');
	}
}
