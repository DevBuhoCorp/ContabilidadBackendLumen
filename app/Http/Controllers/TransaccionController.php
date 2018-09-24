<?php

namespace App\Http\Controllers;

use App\Models\Documentocontable;
use App\Models\Transaccion;
use App\Models\Detalletransaccion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaccionController extends Controller
{

    public function nuevomovimiento(Request $request)
    {
        try {
            if ($request->isJson()) {
                $carbon = Carbon::now('America/Guayaquil');
                $actual = $carbon->toDateTimeString();
                $carbon2 = new Carbon($request->input('Fecha'));
                $fechadoc = $carbon2->toDateString();
                $transaccion = new Transaccion();
                $transaccion->Fecha = $actual;
                $transaccion->save();
                $documento = new Documentocontable();
                $documento->Fecha = $fechadoc;
                $documento->SerieDocumento = $request->input('SerieDocumento');
                $documento->IDTransaccion = $transaccion->ID;
                $documento->save();
                return response()->json([
                    'NumMovimiento' => $transaccion->ID, 'Fecha' => $documento->Fecha->toDateString(), 'DocContable' => $documento->SerieDocumento,
                    'FechaC' => $transaccion->Fecha->toDateString()
                ], 201);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    public function detallemovimiento(Request $request)
    {
        try {
            if ($request->isJson()) {
               // $detalle = Detalletransaccion::create($request->all());
                $detalle = new Detalletransaccion();
                $detalle->IDCuenta = $request->input('IDCuenta');
                $detalle->Etiqueta = $request->input('Etiqueta');
                $detalle->Debe = $request->input('Debe');
                $detalle->Haber = $request->input('Haber');
                $detalle->IDTransaccion = $request->input('IDTransaccion');
                $detalle->save();
                return response()->json($detalle, 201);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    public function listamovimientos(Request $request)
    {
        try {
            if ($request->isJson()) {
                $detalle = new Detalletransaccion();
                $detalle = $detalle->paginate($request->input('psize'));                
                return response()->json($detalle, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }

}
