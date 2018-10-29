<?php

namespace App\Http\Controllers;
use App\Models\Transaccion;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function transpormes()
    {
        DB::statement("SET lc_time_names = 'es_EC'");
        $fecha = Carbon::now();
        $datos = Transaccion::whereRaw('year(Fecha) = ? GROUP BY YEAR(Fecha), MONTH(Fecha) ',[$fecha->year])->get([DB::raw("SUM(Debe) as data, MONTHNAME(Fecha) as label")]);
        return $datos;   
    }

    //
}
