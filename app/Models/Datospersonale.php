<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 26 Oct 2018 20:05:31 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Datospersonale
 * 
 * @property int $ID
 * @property string $Cedula
 * @property string $NombrePrimer
 * @property string $NombreSegundo
 * @property string $ApellidoPaterno
 * @property string $ApellidoMaterno
 * @property string $NumConvencional
 * @property string $NumMovil
 * @property string $Estado
 * @property int $IDUser
 * 
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class Datospersonale extends Eloquent
{
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $casts = [
		'IDUser' => 'int'
	];

	protected $fillable = [
		'Cedula',
		'NombrePrimer',
		'NombreSegundo',
		'ApellidoPaterno',
		'ApellidoMaterno',
		'NumConvencional',
		'NumMovil',
		'Estado',
		'IDUser'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class, 'IDUser');
	}
}
