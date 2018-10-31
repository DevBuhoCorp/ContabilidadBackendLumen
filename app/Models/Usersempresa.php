<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 30 Oct 2018 17:21:14 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Usersempresa
 * 
 * @property int $ID
 * @property int $IDEmpresa
 * @property int $IDUsers
 * @property string $Estado
 * 
 * @property \App\Models\Empresa $empresa
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class Usersempresa extends Eloquent
{
	protected $table = 'usersempresa';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $casts = [
		'IDEmpresa' => 'int',
		'IDUsers' => 'int'
	];

	protected $fillable = [
		'IDEmpresa',
		'IDUsers',
		'Estado'
	];

	public function empresa()
	{
		return $this->belongsTo(\App\Models\Empresa::class, 'IDEmpresa');
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class, 'IDUsers');
	}
}
