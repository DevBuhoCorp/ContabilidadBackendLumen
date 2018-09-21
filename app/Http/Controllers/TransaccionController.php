<?php

namespace App\Http\Controllers;

use App\Models\Documentocontable;
use App\Models\Transaccion;
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
                return response()->json(['NumMovimiento' => $transaccion->ID, 'Fecha' => $documento->Fecha->toDateString(), 'DocContable' => $documento->SerieDocumento,
                    'FechaC' => $transaccion->Fecha->toDateString()], 201);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    public function cabezeratrans(Request $request)
    {
        try {
            if ($request->isJson()) {

            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

}
