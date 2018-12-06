<?php

namespace App\Http\Controllers;

use App\Models\Detalletransaccion;
use App\Models\Documentocontable;
use App\Models\Estacion;
use App\Models\Parametroempresa;
use App\Models\Transaccion;
use App\Models\Cuentacontable;
use App\Models\Plancontable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaccionController extends Controller
{

    public function store_app(Request $request, $empresa)
    {
        try {
            if ($request->isJson()) {
                $results = [
                    "IDREF" => 0
                ];
                $transs = $request->all();

                DB::beginTransaction();
                try {
                    foreach ($transs as $trans) {

                        $transaccion = new Transaccion();
                        $transaccion->IDEstacion = $request->header('estacion')->ID;
                        $transaccion->IDEmpresa = $empresa;
                        $transaccion->fill($trans);
                        $transaccion->save();

                        $detalles = $trans['Detalle'];

                        // Modificar
                        $documento = new Documentocontable();
                        $documento->Fecha = $transaccion->Fecha;
                        $documento->SerieDocumento = $trans['SerieDocumento'];
                        $documento->IDTransaccion = $transaccion->ID;
                        $documento->save();

                        $transaccion->detalletransaccions()->createMany($detalles);

                        foreach ($detalles as $detalle) {
                            $cuentacontable = Cuentacontable::join('plancontable', 'IDCuenta', 'Cuentacontable.ID')->where('plancontable.ID', $detalle["IDCuenta"])->first();
                            $cuentacontable->update(["Saldo" => $cuentacontable["Saldo"] + ($detalle["Debe"] - $detalle["Haber"])]);
                        }
                        $results["IDREF"] = $trans["IDREF"];

                    }
                    DB::commit();
                    $results["EstadoTransaccion"] = "CORRECTO";
                    return response()->json($results, 201);
                } catch (\Exception $e) {
                    DB::rollBack();
                    $results["EstadoTransaccion"] = "ERROR";
                    return response()->json($results, 201);
                }


            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }


    public function store(Request $request, $empresa)
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
                $transaccion->IDEmpresa = $empresa;
                $transaccion->IDUser = $request->user()->ID;
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
                return response()->json($transaccion->ID, 201);

            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    public function query(Request $request)
    {
        $query = Transaccion::where('Transaccion.Estado', $request->input('Estado'))->where('IDEmpresa', $request->input('Empresa'));

        if ($request->input('FInicio')) {
            $FInicio = Carbon::parse($request->input('FInicio'))->toDateString();
            $FFin = ($request->input('FFin')) ? Carbon::parse($request->input('FFin'))->toDateString() : Carbon::now()->toDateString();
            $query->whereBetween(DB::raw('date(Fecha)'), [$FInicio, $FFin]);
        } elseif ($request->input('FFin')) {
            $FFin = Carbon::parse($request->input('FFin'))->toDateString();
            $query->where(DB::raw('date(Fecha)'), '<=', $FFin);
        }

        if ($request->input('ttransaccion')) {
            switch ($request->input('ttransaccion')) {
                case "app":
                    if ($request->input('app')) {
                        $idsEstacion = Estacion::where('IDAplicacion', $request->input('app'))->get(['ID']);
                        $query = $query->whereIn('IDEstacion', $idsEstacion);
                    } else {
                        $query = $query->WhereNotNull('IDEstacion');
                    }
                    break;
                case "manual":
                    $query = $query->whereNull('IDEstacion');
                    break;
            }
        }
        return $query;
    }

    public function index(Request $request)
    {
        try {
            if ($request->isJson()) {

                $query = $this->query($request);
                $totales = ["Debe" => $query->sum('Debe'), "Haber" => $query->sum('Haber')];
                $transacciones = $query->paginate($request->input('psize'));

                return response()->json(["data" => $transacciones, "totales" => $totales], 200);
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
                    ->select(DB::raw("CONCAT(cc.NumeroCuenta, ' ',cc.Etiqueta) as Cuenta,detalletransaccion.Etiqueta,detalletransaccion.Debe,detalletransaccion.Haber"))
                    ->paginate($request->input('psize'));
                return response()->json($detalles, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    public function transporcuenta($id, Request $request)
    {
        try {
            if ($request->isJson()) {
                $totales = Detalletransaccion::join('plancontable as pc', 'detalletransaccion.IDCuenta', '=', 'pc.ID')
                    ->where('pc.IDCuenta', $id)->get();

                $query = Detalletransaccion::join('plancontable as pc', 'detalletransaccion.IDCuenta', '=', 'pc.ID')
                    ->join('cuentacontable as cc', 'pc.IDCuenta', '=', 'cc.ID')
                    ->join('transaccion as t', 'detalletransaccion.IDTransaccion', '=', 't.ID')
                    ->where('pc.IDCuenta', $id)
                    ->select(DB::raw("detalletransaccion.Debe,detalletransaccion.Haber,t.Etiqueta as Transaccion, t.Fecha"));


                if ($request->input('FInicio')) {
                    $FInicio = Carbon::parse($request->input('FInicio'))->toDateString();
                    $FFin = ($request->input('FFin')) ? Carbon::parse($request->input('FFin'))->toDateString() : Carbon::now()->toDateString();
                    $query->whereBetween(DB::raw('date(t.Fecha)'), [$FInicio, $FFin]);
                } elseif ($request->input('FFin')) {
                    $FFin = Carbon::parse($request->input('FFin'))->toDateString();
                    $query->where(DB::raw('date(t.Fecha)'), '<=', $FFin);
                }

                if ($request->input('ttransaccion')) {
                    switch ($request->input('ttransaccion')) {
                        case "app":
                            if ($request->input('app')) {
                                $idsEstacion = Estacion::where('t.IDAplicacion', $request->input('app'))->get(['ID']);
                                $query = $query->whereIn('t.IDEstacion', $idsEstacion);
                            } else {
                                $query = $query->WhereNotNull('t.IDEstacion');
                            }
                            break;
                        case "manual":
                            $query = $query->whereNull('t.IDEstacion');
                            break;
                    }
                }


                $detalles = $query->paginate($request->input('psize'));

                $detalles->TotalDebe = $detalles->sum('Debe');
                $sumadebe = $totales->sum('Debe');
                $sumahaber = $totales->sum('Haber');
                //return response()->json($detalles, 200);
                return response()->json(['detalles' => $detalles, 'Debe' => $sumadebe, 'Haber' => $sumahaber, 'Saldo' => $sumadebe - $sumahaber], 200);
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

    public static function balanceComprobacion($empresa)
    {
        // $rows = DB::select('CALL balance_comprobacion(?)', [ $modplanc ]);
        $parametro = Parametroempresa::where('IDEmpresa', $empresa)->first();

        $comprobacion = CuentaContable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
            ->join('modeloplancontable', 'plancontable.IDModelo', '=', 'modeloplancontable.ID')
            ->join('detalletransaccion', 'plancontable.ID', '=', 'detalletransaccion.IDCuenta')
            ->join('transaccion', 'detalletransaccion.IDTransaccion', '=', 'transaccion.ID')
            ->where('modeloplancontable.ID', $parametro->Valor)
            ->where('transaccion.Estado', 'ACT')
            ->groupBy('cuentacontable.ID')
            ->orderBy('cuentacontable.NumeroCuenta')
            ->get([DB::raw("cuentacontable.ID,
		CONCAT(cuentacontable.NumeroCuenta,' ',cuentacontable.Etiqueta) Etiqueta,		
		sum(detalletransaccion.Debe) Debe,
		sum(detalletransaccion.Haber) Haber,
		IF(cuentacontable.Saldo > 0, cuentacontable.Saldo, 0) Deudor,
		IF(cuentacontable.Saldo < 0, ABS(cuentacontable.Saldo), 0) Acreedor")]);
        return $comprobacion;
    }


    public function estadoresultado(Request $request, $modplanc)
    {
        $rows = DB::select('CALL estadoresultado(?)', [$modplanc]);
        return Response($rows, 200);
    }



}
