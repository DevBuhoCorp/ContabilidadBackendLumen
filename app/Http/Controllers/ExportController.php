<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Transaccion;
use App\Models\Detalletransaccion;
use App\Models\Cuentabalance;
use App\Models\Cuentacontable;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    public function exportlibrodiario(Request $request)
    {
        try {
            if ($request->isJson()) {
                $query = Transaccion::join('estacion as e', 'transaccion.IDEstacion', '=', 'e.ID')
                ->join('aplicacion as a','e.IDAplicacion','=','a.ID')
                ->where('transaccion.Estado', 'ACT')->where('transaccion.IDEmpresa', 1)->get(['transaccion.Fecha','e.Nmaquina as Estacion','a.Descripcion as Aplicación','transaccion.Etiqueta as Transacción','transaccion.Debe','transaccion.Haber']);
                return response()->json($query, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    public function exportdetalletrans($id, Request $request)
    {
        try {
            if ($request->isJson()) {
                $detalles = Detalletransaccion::join('plancontable as pc', 'detalletransaccion.IDCuenta', '=', 'pc.ID')
                    ->join('cuentacontable as cc', 'pc.IDCuenta', '=', 'cc.ID')
                    ->where('detalletransaccion.IDTransaccion', $id)
                    ->select(DB::raw("CONCAT(cc.NumeroCuenta, ' ',cc.Etiqueta) as Cuenta,detalletransaccion.Etiqueta,detalletransaccion.Debe,detalletransaccion.Haber"))->get();
                return response()->json($detalles, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    public function exportlibromayor($id, Request $request)
    {
        try {
            if ($request->isJson()) {
            
                $query = Detalletransaccion::join('plancontable as pc', 'detalletransaccion.IDCuenta', '=', 'pc.ID')
                    ->join('cuentacontable as cc','pc.IDCuenta','=','cc.ID')
                    ->join('transaccion as t','detalletransaccion.IDTransaccion','=','t.ID')
                    ->where('pc.IDCuenta', $id)
                    ->select(DB::raw("detalletransaccion.Debe,detalletransaccion.Haber,t.Etiqueta as Transaccion, t.Fecha"))->get();

               
                return response()->json($query, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    public function exportbalancefinal(){
        $cuentas = Cuentabalance::join('plancontable', 'plancontable.ID', '=', 'cuentabalance.IDPlanContable')
        ->join('cuentacontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
        ->where('cuentabalance.IDBalance',2)->get([DB::raw("CONCAT(cuentacontable.NumeroCuenta,' ',cuentacontable.Etiqueta) as Cuenta"),DB::raw('ABS(cuentacontable.Saldo) as Saldo')]);

        return response()->json($cuentas, 201);
    }

    public function exportestadoresultado(Request $request, $modplancontable)
    {
        $resultado = Cuentacontable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
            ->join('modeloplancontable', 'plancontable.IDModelo', '=', 'modeloplancontable.ID')
            ->where('modeloplancontable.ID', $modplancontable)
            ->where('cuentacontable.IDTipoEstado', 1)
            ->where('cuentacontable.Saldo', '!=', 0)
            ->groupBy('cuentacontable.ID')
            ->orderBy('cuentacontable.NumeroCuenta')
            ->get([DB::raw("CONCAT(cuentacontable.NumeroCuenta,' ', cuentacontable.Etiqueta) Cuenta"),  DB::raw("IF(cuentacontable.Saldo > 0, cuentacontable.Saldo, 0) Deudor"),DB::raw("IF(cuentacontable.Saldo < 0, ABS(cuentacontable.Saldo), 0) Acreedor")]);
       
        return response()->json($resultado, 201);
    }
}
