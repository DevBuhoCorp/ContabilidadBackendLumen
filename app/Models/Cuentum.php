<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 16 Nov 2018 19:58:54 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Cuentum
 * 
 * @property int $ID
 * @property int $IDPadre
 * @property string $Numero
 * @property string $Etiqueta
 *
 * @package App\Models
 */
class Cuentum extends Eloquent
{
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $casts = [
		'IDPadre' => 'int'
	];

	protected $fillable = [
		'IDPadre',
		'NumeroCuenta',
		'Etiqueta'
	];
}
