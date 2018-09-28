<?php

namespace App\Http\Controllers;

use App\Models\Documentocontable;
use App\Models\Transaccion;
use App\Models\Detalletransaccion;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
                $transaccion->Estado = $request->all()['Cabecera'][0]['Estado'] ? 'ACT' : 'INA';
                $transaccion->save();
                $documento = new Documentocontable();
                $documento->Fecha = $fechadoc;
                $documento->SerieDocumento = $request->all()['Cabecera'][0]['SerieDocumento'];
                $documento->IDTransaccion = $transaccion->ID;
                $documento->save();
                for ($i = 0; $i < count($detalles); $i++) {
                    $detalles[$i]["IDTransaccion"] = $documento->IDTransaccion;
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
                $transacciones = Transaccion::where('Estado','ACT')->paginate($request->input('psize'));
                return response()->json($transacciones, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }





}
