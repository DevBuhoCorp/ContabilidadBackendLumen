<?php

namespace App\Http\Controllers;

use App\Models\Cuentabalance;
use App\Models\Tipobalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoBalanceController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $modelo, $balance)
    {
        try {
            if ($request->isJson()) {
                // Eliminando Cuentas
                Cuentabalance::join('plancontable', 'plancontable.ID', '=', 'cuentabalance.IDPlanContable')
                    ->where('cuentabalance.IDBalance', $balance)
                    ->where('plancontable.IDModelo', $modelo)->delete();

                $datos = DB::table('cuentabalance')->insert( $request->all() );
                return response()->json($datos, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    public function combo()
    {
        try {
            $tipoBalances = Tipobalance::all();
            return response()->json($tipoBalances, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }
}
