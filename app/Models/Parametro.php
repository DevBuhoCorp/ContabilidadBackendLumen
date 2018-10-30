<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 29 Oct 2018 16:47:59 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Parametro
 * 
 * @property int $ID
 * @property int $Valor
 * @property string $Descripcion
 * @property string $Abr
 * @property string $Estado
 *
 * @package App\Models
 */
class Parametro extends Eloquent
{
	protected $table = 'parametro';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $fillable = [
		'Descripcion',
		'Abr',
		'Valor',
		'Estado'
	];
}
