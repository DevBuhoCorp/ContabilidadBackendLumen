<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 29 Oct 2018 16:47:59 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Grupopersonalizado
 * 
 * @property int $ID
 * @property string $Codigo
 * @property string $Etiqueta
 * @property string $Comentario
 * @property string $GrupoPersonalizadocol
 * @property bool $Calculado
 * @property string $Formula
 * @property string $Estado
 * 
 * @property \Illuminate\Database\Eloquent\Collection $listagrupopersonalizados
 *
 * @package App\Models
 */
class Grupopersonalizado extends Eloquent
{
	protected $table = 'grupopersonalizado';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $casts = [
		'Calculado' => 'bool'
	];

	protected $fillable = [
		'Codigo',
		'Etiqueta',
		'Comentario',
		'GrupoPersonalizadocol',
		'Calculado',
		'Formula',
		'Estado'
	];

	public function listagrupopersonalizados()
	{
		return $this->hasMany(\App\Models\Listagrupopersonalizado::class, 'IDGrupo');
	}
}
