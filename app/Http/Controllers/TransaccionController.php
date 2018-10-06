<?php

namespace App\Http\Controllers;

use App\Models\Detalletransaccion;
use App\Models\Documentocontable;
use App\Models\Estacion;
use App\Models\Transaccion;
use App\Models\Cuentacontable;
use App\Models\Plancontable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaccionController extends Controller
{
    public function store(Request $request)
    {
        try {
            if ($request->isJson()) {
                $detalles = $request->all()['Detalle'];
                $carbon = Carbon::now('America/Guayaquil');
                $actual = $carbon->toDateTimeString();
                $carbon2 = new Carbon($request->all()['Cabecera'][0]['Fecha']);
                $fechadoc = $carbon2->toDateString();
                $transaccion = new Transaccion();
                $transaccion->Fecha = $actual;
                // Modificar
                $transaccion->IDEmpresa = 2;
                $transaccion->Estado = $request->all()['Cabecera'][0]['Estado'] ? 'ACT' : 'INA';
                $transaccion->Etiqueta = $request->all()['Cabecera'][0]['Etiqueta'];
                $transaccion->Debe = $request->all()['Cabecera'][0]['Debe'];
                $transaccion->Haber = $request->all()['Cabecera'][0]['Haber'];
                $transaccion->save();
                $documento = new Documentocontable();
                $documento->Fecha = $fechadoc;
                $documento->SerieDocumento = $request->all()['Cabecera'][0]['SerieDocumento'];
                $documento->IDTransaccion = $transaccion->ID;
                $documento->save();
                for ($i = 0; $i < count($detalles); $i++) {
                    $detalles[$i]["IDTransaccion"] = $documento->IDTransaccion;
                    $planc = Plancontable::find($detalles[$i]["IDCuenta"]);
                    $cuentacontable = Cuentacontable::find($planc->IDCuenta);
                    $cuentacontable->Saldo = $cuentacontable->Saldo + ($detalles[$i]["Debe"] - $detalles[$i]["Haber"]);
                    $cuentacontable->save();
                    /*$cuentacontablepadre = Cuentacontable::find($cuentacontable->IDPadre);
                    $cuentacontablepadre->Saldo = $cuentacontablepadre->Saldo + $cuentacontable->Saldo ;
                    $cuentacontablepadre->save();*/
                    $cuentacontablepadre = Cuentacontable::find($cuentacontable->IDPadre);
                    do {
                        $cuentacontablepadre->Saldo = $cuentacontablepadre->Saldo + ($detalles[$i]["Debe"] - $detalles[$i]["Haber"]);
                        $cuentacontablepadre->save();
                        $cuentacontablepadre = CuentaContable::find($cuentacontablepadre->IDPadre);
                    } while ($cuentacontablepadre);
                }
                $detalles = Detalletransaccion::insert($detalles);
                return response()->json($detalles, 201);

            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    public function index(Request $request)
    {
        try {
            if ($request->isJson()) {

                dd($request->all());

                $query = Transaccion::where('Estado', 'ACT')->where('IDEmpresa', 2);

                if ($request->input('ttransaccion')) {
                    switch ($request->input('ttransaccion')) {
                        case "app":
                            if ($request->input('app')) {
                                $idsEstacion = Estacion::where('IDAplicacion', $request->input('app'))->get([ 'ID' ]);
                                $query = $query->whereIn('IDEstacion', $idsEstacion);
                            } else {
                                $query = $query->whereNotNull('IDEstacion');
                            }
                            break;
                        case "manual":
                            $query = $query->whereNull('IDEstacion');
                            break;
                    }

                }


                $transacciones = $query->paginate($request->input('psize'));

                return response()->json($transacciones, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    public function show($id, Request $request)
    {
        try {
            if ($request->isJson()) {
                /* $detalles = Detalletransaccion::
                     where('IDTransaccion', $id)
                     ->paginate($request->input('psize'));*/

                $detalles = Detalletransaccion::join('plancontable as pc', 'detalletransaccion.IDCuenta', '=', 'pc.ID')
                    ->join('cuentacontable as cc', 'pc.IDCuenta', '=', 'cc.ID')
                    ->where('detalletransaccion.IDTransaccion', $id)
                    ->select(DB::raw("cc.Etiqueta as Cuenta,detalletransaccion.Etiqueta,detalletransaccion.Debe,detalletransaccion.Haber"))
                    ->paginate($request->input('psize'));
                return response()->json($detalles, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if ($request->isJson()) {
                $transaccion = Transaccion::find($id);
                $transaccion->Estado = $request->input('Estado') ? 'ACT' : 'INA';
                $transaccion->save();
                return response()->json($transaccion, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    public function total(Request $request)
    {
        try {
            if ($request->isJson()) {
                $transaccion[0] = Transaccion::sum('Debe');
                $transaccion[1] = Transaccion::sum('Haber');
                return response()->json(['Debe' => $transaccion[0], 'Haber' => $transaccion[1]], 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }

}
