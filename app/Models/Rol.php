<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 26 Oct 2018 20:05:31 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Rol
 * 
 * @property int $ID
 * @property string $Descripcion
 * @property string $Observacion
 * @property string $Estado
 *
 * @package App\Models
 */
class Rol extends Eloquent
{
	protected $table = 'rol';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $fillable = [
		'Descripcion',
		'Observacion',
		'Estado'
	];
}
