<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 12 Oct 2018 15:43:36 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Empresa
 *
 * @property int $ID
 * @property string $Descripcion
 * @property string $Observacion
 * @property string $RUC
 * @property string $RazonSocial
 * @property string $NombreComercial
 * @property string $TipoContribuyente
 * @property bool $ObligContabilidad
 * @property bool $ContEspecial
 * @property string $Direccion
 * @property string $Telefono
 * @property string $Celular
 * @property string $Email
 * @property string $Estado
 *
 * @property \Illuminate\Database\Eloquent\Collection $cuentabancaria
 * @property \Illuminate\Database\Eloquent\Collection $aplicacions
 *
 * @package App\Models
 */
class Empresa extends Eloquent
{
    protected $table = 'empresa';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $casts = [
        'ObligContabilidad' => 'bool',
        'ContEspecial' => 'bool'
    ];

    protected $fillable = [
        'Descripcion',
        'Observacion',
        'RUC',
        'RazonSocial',
        'NombreComercial',
        'TipoContribuyente',
        'ObligContabilidad',
        'ContEspecial',
        'Direccion',
        'Telefono',
        'Celular',
        'Email',
        'Estado'
    ];

    public function cuentabancaria()
    {
        return $this->hasMany(\App\Models\Cuentabancarium::class, 'IDEmpresa');
    }

    public function aplicacions()
    {
        return $this->belongsToMany(\App\Models\Aplicacion::class, 'empresaaplicacion', 'IDEmpresa', 'IDAplicacion')
            ->withPivot('ID');
    }
}
