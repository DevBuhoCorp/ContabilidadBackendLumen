<?php

namespace App\Http\Controllers;

use App\Models\Cuentacontable;
use App\Models\Plancontable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CuentaContableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $cuentas = CuentaContable::all();
            return response()->json($cuentas, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    public function autocomplete(Request $request)
    {
        try {
            if ($request->isJson()) {
                $cuentas = PlanContable::join('cuentacontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
                    ->where('cuentacontable.IDGrupoCuenta',2)
                    ->where('plancontable.IDModelo', $request->input('Modelo'))
                    
                    //->select(DB::raw('cuentacontable.ID,cuentacontable.NumeroCuenta,cuentacontable.Etiqueta'))->get();
                    ->select(DB::raw("plancontable.ID,CONCAT(cuentacontable.NumeroCuenta,' ',cuentacontable.Etiqueta) as cuenta"))->orderBy('NumeroCuenta', 'asc')
                    ->get();;
                    


                /*$cuentas = $cuentas->map(function ($row) {
                    return $row['NumeroCuenta'] . ' ' . $row['Etiqueta'];

                });*/
                return response()->json($cuentas, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if ($request->isJson()) {

                $cuenta = Cuentacontable::create($request->all());
                $cuenta->save();

                // Plan Contable
                $planC = new Plancontable;
                $planC->IDCuenta = $cuenta->ID;
                $planC->IDModelo = $request->input("IDPlanContable");
                $planC->ncuenta = 0;
                $planC->save();

                //
                if ($cuenta->IDPadre) {
                    $planC = Plancontable::where(["IDModelo" => $request->input("IDPlanContable"), "IDCuenta" => $cuenta->IDPadre])->get()[0];
                    $planC->ncuenta = $planC->ncuenta + 1;
                    $planC->save();
                    $cuentaup = CuentaContable::find($cuenta->IDPadre);
                    $cuentaup->IDGrupoCuenta = 1;
                    //$cuentaup->Saldo = $cuentaup->Saldo + $cuenta->Saldo;
                    do {
                        $cuentaup->Saldo = $cuentaup->Saldo + $cuenta->Saldo;
                        $cuentaup->save();
                        $cuentaup = CuentaContable::find($cuentaup->IDPadre);
                    } while ($cuentaup);
                    //$cuentaup->save();
                } else {
                    $planC = Plancontable::where(["IDModelo" => $request->input("IDPlanContable"), "IDCuenta" => $cuenta->ID])->get()[0];
                    $planC->save();
                }

                return response()->json($cuenta, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $cuenta = Cuentacontable::find($id);
            return response()->json($cuenta, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getNumCuenta($plancontable, $id)
    {
        try {
            $secuencia = DB::select('call getNumCuenta(?,?)', [$id, $plancontable]);
            return response()->json($secuencia, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    public function MaxPadre(Request $request)
    {
        try {
            //$numerocuenta = (Cuentacontable::whereNull('IDPadre')->max('NumeroCuenta')) + 1;
            $numerocuenta = Cuentacontable::
                join('plancontable', 'IDCuenta', '=', 'CuentaContable.ID')
                ->where('IDModelo', $request->input('Modelo'))
                ->whereNull('IDPadre')->max(DB::raw('CAST(NumeroCuenta AS SIGNED)'));
            return response()->json($numerocuenta + 1, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    public function drag(Request $request)
    {
        try {
            $cuentas = Cuentacontable::select('ID AS data', 'Etiqueta AS label')->whereNotExists(function ($query) use ($request) {
                $query->select(DB::raw(1))
                    ->from('plancontable')
                    ->whereRaw('IDModelo = ' . $request->input("id"))->whereRaw('cuentacontable.ID = IDCuenta');
            })->get();

            return Response()->json($cuentas, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            if ($request->isJson()) {
                $cuenta = Cuentacontable::find($id);
                $cuenta->Etiqueta = $request->input('Etiqueta');
                $cuenta->Estado = $request->input('Estado');
                $cuenta->IDDiario = $request->input('IDDiario');
                $cuenta->save();
                return response()->json($cuenta, 200);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
