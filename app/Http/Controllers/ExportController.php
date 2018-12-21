<?php

namespace App\Http\Controllers;

use App\Models\Cuentabalance;
use App\Models\Cuentacontable;
use App\Models\Detalletransaccion;
use App\Models\Parametroempresa;
use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ExportController extends Controller
{
    public function exportlibrodiario(Request $request)
    {
        try {
            if ($request->isJson()) {
                $query = Transaccion::join('estacion as e', 'transaccion.IDEstacion', '=', 'e.ID')
                    ->join('aplicacion as a', 'e.IDAplicacion', '=', 'a.ID')
                    ->where('transaccion.Estado', 'ACT')->where('transaccion.IDEmpresa', 1)->get(['transaccion.Fecha', 'e.Nmaquina as Estacion', 'a.Descripcion as Aplicación', 'transaccion.Etiqueta as Transacción', 'transaccion.Debe', 'transaccion.Haber']);
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
                    ->join('cuentacontable as cc', 'pc.IDCuenta', '=', 'cc.ID')
                    ->join('transaccion as t', 'detalletransaccion.IDTransaccion', '=', 't.ID')
                    ->where('pc.IDCuenta', $id)
                    ->select(DB::raw("detalletransaccion.Debe,detalletransaccion.Haber,t.Etiqueta as Transaccion, t.Fecha"))->get();

                return response()->json($query, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    /* public function exportbalancefinal()
    {
        $cuentas = Cuentabalance::join('plancontable', 'plancontable.ID', '=', 'cuentabalance.IDPlanContable')
            ->join('cuentacontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
            ->where('cuentabalance.IDBalance', 2)->get([DB::raw("CONCAT(cuentacontable.NumeroCuenta,' ',cuentacontable.Etiqueta) as Cuenta"), DB::raw('ABS(cuentacontable.Saldo) as Saldo')]);

        return response()->json($cuentas, 201);
    } */

    /* public function exportestadoresultado(Request $request, $modplancontable)
    {
        $resultado = Cuentacontable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
            ->join('modeloplancontable', 'plancontable.IDModelo', '=', 'modeloplancontable.ID')
            ->where('modeloplancontable.ID', $modplancontable)
            ->where('cuentacontable.IDTipoEstado', 1)
            ->where('cuentacontable.Saldo', '!=', 0)
            ->groupBy('cuentacontable.ID')
            ->orderBy('cuentacontable.NumeroCuenta')
            ->get([DB::raw("CONCAT(cuentacontable.NumeroCuenta,' ', cuentacontable.Etiqueta) Cuenta"), DB::raw("IF(cuentacontable.Saldo > 0, cuentacontable.Saldo, 0) Deudor"), DB::raw("IF(cuentacontable.Saldo < 0, ABS(cuentacontable.Saldo), 0) Acreedor")]);

        return response()->json($resultado, 201);
    } */

    public function exportDiarioContable(Request $request)
    {
        try {
            if (true) {

                $query = (new TransaccionController())->query($request);
                $transacciones = $query->with('detalletransaccions_v2')->get(['ID', 'Fecha', 'Etiqueta', 'Debe', 'Haber']);

                $totales = ["Debe" => $query->sum('Transaccion.Debe'), "Haber" => $query->sum('Transaccion.Haber')];

                return Excel::load('app/Files/LibroDiario.xlsx', function ($reader) use ($request, $transacciones, $totales) {

                    $sheet = $reader->getActiveSheet();

                    $Cabecera = json_decode($request->input('Cabecera'), true);

                    $sheet->setCellValue('C3', $Cabecera["FInicio"]);
                    $sheet->setCellValue('C4', $Cabecera["FFin"]);
                    $sheet->setCellValue('C5', $Cabecera["Tcuenta"]);

                    $sheet->setCellValue('F3', $Cabecera["Ttransaccion"]);
                    $sheet->setCellValue('F4', $Cabecera["App"]);

                    $sheet->setCellValue('C9', $totales["Debe"]);
                    $sheet->setCellValue('F9', $totales["Haber"]);

                    $sheet->setCellValue('F7', count($transacciones));
                    $row = 12;

                    foreach ($transacciones as $transacion) {
                        $sheet->setCellValue('B' . $row, $transacion->Fecha);

                        $rows = $transacion->detalletransaccions_v2->map(function ($x) {
                            return [$x->NumeroCuenta, $x->Etiqueta, $x->Debe, $x->Haber];
                        })->toArray();
                        $sheet->fromArray($rows, null, 'C' . $row, true);
                        $row += count($transacion->detalletransaccions_v2);

                        $sheet->mergeCells('B' . $row . ':D' . $row);

                        $sheet->getStyle('B' . $row . ':F' . $row)
                            ->applyFromArray([
                                'font' => [

                                    'bold' => true,
                                ],
                            ]);
                        // getAlignment()->setHorizontal('center');
                        $sheet->setCellValue('B' . $row, $transacion->Etiqueta);
                        $sheet->setCellValue('E' . $row, $transacion->Debe);
                        $sheet->setCellValue('F' . $row, $transacion->Haber);
                        $row += 2;

                    }

                })->download('xlsx', ['Access-Control-Allow-Origin' => '*']);

            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    public function exportBalanceComprobacion(Request $request, $empresa)
    {
        try {

            //$balance = TransaccionController::balanceComprobacion($empresa);
            $parametro = Parametroempresa::where('IDEmpresa', $empresa)->first();

            $balance = Cuentacontable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
                ->join('modeloplancontable', 'plancontable.IDModelo', '=', 'modeloplancontable.ID')
                ->join('detalletransaccion', 'plancontable.ID', '=', 'detalletransaccion.IDCuenta')
                ->join('transaccion', 'detalletransaccion.IDTransaccion', '=', 'transaccion.ID')
                ->where('modeloplancontable.ID', $parametro->Valor)
                ->where('transaccion.Estado', 'ACT')
                ->groupBy('cuentacontable.ID')
                ->orderBy('cuentacontable.NumeroCuenta')
                ->get([DB::raw("
                    cuentacontable.NumeroCuenta,
                    cuentacontable.Etiqueta,
                    sum(detalletransaccion.Debe) Debe,
                    sum(detalletransaccion.Haber) Haber,
                    IF(cuentacontable.Saldo > 0, cuentacontable.Saldo, 0) Deudor,
                    IF(cuentacontable.Saldo < 0, ABS(cuentacontable.Saldo), 0) Acreedor")]);

            return Excel::load('app/Files/BalanceComprobacion.xlsx', function ($reader) use ($request, $balance) {

                $sheet = $reader->getActiveSheet();
                $sheet->setCellValue('B3', 'AL '. Carbon::now());
                $sheet->setCellValue('D4', $balance->sum('Debe'));
                $sheet->setCellValue('E4', $balance->sum('Haber'));
                $sheet->setCellValue('F4', $balance->sum('Deudor'));
                $sheet->setCellValue('G4', $balance->sum('Acreedor'));

                $row = 8;

                $sheet->fromArray($balance->toArray(), null, 'B' . $row, true);

            })->download('xlsx', ['Access-Control-Allow-Origin' => '*']);
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    public function exportBalanceFinal(Request $request, $empresa)
    {
        try {

            //$balance = TransaccionController::balanceComprobacion($empresa);
            $parametro = Parametroempresa::where('IDEmpresa', $empresa)->first();
            
            $activos = Cuentabalance::join('plancontable', 'plancontable.ID', '=', 'cuentabalance.IDPlanContable')
            ->join('cuentacontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
            ->where('cuentacontable.NumeroCuenta','like','1%')
            ->where('plancontable.IDModelo', $parametro->Valor )
            ->where('cuentabalance.IDBalance',2)->get(['cuentacontable.Etiqueta',DB::raw('ABS(cuentacontable.Saldo) as Saldo')]);
    
            $pasivos = Cuentabalance::join('plancontable', 'plancontable.ID', '=', 'cuentabalance.IDPlanContable')
            ->join('cuentacontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
            ->where('cuentacontable.NumeroCuenta','like','2%')
                ->where('plancontable.IDModelo', $parametro->Valor )
            ->where('cuentabalance.IDBalance',2)->get(['cuentacontable.Etiqueta',DB::raw('ABS(cuentacontable.Saldo) as Saldo')]);
    
            $patrimonio = Cuentabalance::join('plancontable', 'plancontable.ID', '=', 'cuentabalance.IDPlanContable')
            ->join('cuentacontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
            ->where('cuentacontable.NumeroCuenta','like','3%')
                ->where('plancontable.IDModelo', $parametro->Valor )
            ->where('cuentabalance.IDBalance',2)->get(['cuentacontable.Etiqueta',DB::raw('ABS(cuentacontable.Saldo) as Saldo')]);
        

            return Excel::load('app/Files/BalanceFinal.xlsx', function ($reader) use ($request, $activos, $pasivos, $patrimonio) {

                $sheet = $reader->getActiveSheet();
                
                $row = 8;

                $sheet->fromArray($activos->toArray(), null, 'B' . $row, true);
                $sheet->fromArray($pasivos->toArray(), null, 'D' . $row, true);
                $sheet->fromArray($patrimonio->toArray(), null, 'F' . $row, true);

            })->download('xlsx', ['Access-Control-Allow-Origin' => '*']);
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    public function exportEstadoResultado(Request $request, $empresa)
    {
        try {

            //$balance = TransaccionController::balanceComprobacion($empresa);
            
            $estado = ReportEstadoController::estado_resultado($empresa);
        

            return Excel::load('app/Files/EstadoResultado.xlsx', function ($reader) use ($request, $estado) {

                $sheet = $reader->getActiveSheet();
                
                $row = 7;

                $sheet->fromArray($estado["resultado2"], null, 'B' . $row, true);

            })->download('xlsx', ['Access-Control-Allow-Origin' => '*']);
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }
}
