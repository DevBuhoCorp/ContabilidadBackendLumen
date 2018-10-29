<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 29 Oct 2018 16:47:59 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $IDRol
 * 
 * @property \App\Models\Rol $rol
 * @property \Illuminate\Database\Eloquent\Collection $datospersonales
 *
 * @package App\Models
 */
class User extends Eloquent
{
	protected $casts = [
		'IDRol' => 'int'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'password',
		'remember_token',
		'IDRol'
	];

	public function rol()
	{
		return $this->belongsTo(\App\Models\Rol::class, 'IDRol');
	}

	public function datospersonales()
	{
		return $this->hasMany(\App\Models\Datospersonale::class, 'IDUser');
	}
}
