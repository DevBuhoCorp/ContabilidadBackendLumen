<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 31 Oct 2018 22:30:22 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Parametroempresa
 * 
 * @property int $ID
 * @property int $IDEmpresa
 * @property string $Descripcion
 * @property string $Abr
 * @property int $Valor
 * @property string $Estado
 *
 * @package App\Models
 */
class Parametroempresa extends Eloquent
{
	protected $table = 'parametroempresa';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $casts = [
		'Valor' => 'int'
	];

	protected $fillable = [
		'Descripcion',
		'IDEmpresa',
		'Abr',
		'Valor',
		'Estado'
	];
}
