<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Cuentacontable;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function transpormes($empresa)
    {
        DB::statement("SET lc_time_names = 'es_EC'");
        $fecha = Carbon::now();
        $datos = Transaccion::whereRaw('IDEmpresa = ? and year(Fecha) = ? GROUP BY YEAR(Fecha), MONTH(Fecha) ', [$empresa, $fecha->year])->get([DB::raw("SUM(Debe) as data, MONTHNAME(Fecha) as label")]);
        return response()->json($datos);
    }

    public function topcuentas($modelo)
    {
        $datos = Cuentacontable::join('plancontable as pc', 'pc.IDCuenta', '=', 'cuentacontable.ID')
            ->whereRaw('pc.IDModelo = ? and cuentacontable.IDGrupoCuenta= 2 ORDER BY cuentacontable.Saldo desc limit 5', [$modelo])
            ->get(['cuentacontable.Etiqueta', 'cuentacontable.Saldo']);
        return response()->json($datos);
    }

    public function porcentajemes($empresa)
    {
        //DB::statement("SET lc_time_names = 'es_EC'");
        $fecha = Carbon::now();
        $saldos = Transaccion::whereRaw('IDEmpresa = ? and year(Fecha) = ? and MONTH(Fecha) BETWEEN ? AND ? GROUP BY YEAR(Fecha), MONTH(Fecha) ', [$empresa, $fecha->year,$fecha->month -1,$fecha->month])->get([DB::raw("SUM(Debe) as data,MONTHNAME(Fecha) as label")]);
        return response()->json($saldos);
    }

    public function topmovimientos($modelo)
    {
        $datos = Cuentacontable::join('plancontable as pc', 'pc.IDCuenta', '=', 'cuentacontable.ID')
            ->join('detalletransaccion as d','d.IDCuenta','=','pc.ID')
            ->whereRaw('pc.IDModelo = ? and cuentacontable.IDGrupoCuenta= 2 GROUP BY d.IDCuenta ORDER BY data desc limit 5', [$modelo])
            ->get([DB::raw('cuentacontable.Etiqueta as label'), DB::raw('count(d.IDCuenta) AS data')]);
        return response()->json($datos);
    }

    //
}
