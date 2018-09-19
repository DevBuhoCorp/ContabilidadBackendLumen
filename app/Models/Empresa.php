<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 13 Sep 2018 22:19:14 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Empresa
 * 
 * @property int $ID
 * @property string $Descripcion
 * @property string $Observacion
 * @property string $Estado
 * 
 * @property \Illuminate\Database\Eloquent\Collection $aplicacions
 *
 * @package App\Models
 */
class Empresa extends Eloquent
{
	protected $table = 'empresa';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $fillable = [
		'Descripcion',
		'Observacion',
		'Estado'
	];

	public function aplicacions()
	{
		return $this->belongsToMany(\App\Models\Aplicacion::class, 'empresaaplicacion', 'IDEmpresa', 'IDAplicacion')
					->withPivot('ID');
	}
}
