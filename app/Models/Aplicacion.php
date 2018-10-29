<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 29 Oct 2018 16:47:59 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Aplicacion
 * 
 * @property int $ID
 * @property string $Descripcion
 * @property string $Observacion
 * @property string $Estado
 * 
 * @property \Illuminate\Database\Eloquent\Collection $empresas
 * @property \Illuminate\Database\Eloquent\Collection $estacions
 *
 * @package App\Models
 */
class Aplicacion extends Eloquent
{
	protected $table = 'aplicacion';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $fillable = [
		'Descripcion',
		'Observacion',
		'Estado'
	];

	public function empresas()
	{
		return $this->belongsToMany(\App\Models\Empresa::class, 'empresaaplicacion', 'IDAplicacion', 'IDEmpresa')
					->withPivot('ID');
	}

	public function estacions()
	{
		return $this->hasMany(\App\Models\Estacion::class, 'IDAplicacion');
	}
}
