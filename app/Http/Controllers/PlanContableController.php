<?php

namespace App\Http\Controllers;

use App\Models\Cuentabalance;
use App\Models\Cuentacontable;
use App\Models\Modeloplancontable;
use App\Models\Parametroempresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanContableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->isJson()) {

//                $parametro = Parametroempresa::where('IDEmpresa', $request->input('Empresa'))->first();
                $idModelo = ($request->input('Empresa')) ? Parametroempresa::where('IDEmpresa', $request->input('Empresa'))->first()->Valor : $request->input('Modelo');
                $planc = DB::select('SELECT fn_Sel_PlanContable(?,?) data;', [0, $idModelo]);
                return response()->json($planc, 200);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

    public function numerocuenta(Request $request)
    {
        try {
            $planc = DB::select('call getNumCuenta(?,?)', [$request->input('padre'), $request->input('plancontable')]);
            return response()->json($planc, 200);

            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $modelo
     * @param  int $balance
     * @return \Illuminate\Http\Response
     */
    public function Modelo_Balance_PlanCuenta(Request $request)
    {
        try {
            $cuentas = Cuentabalance::
            join('plancontable', 'IDPlanContable', '=', 'plancontable.ID')
                ->where('IDBalance', $request->input("balance"))
                ->where('IDModelo', $request->input("modelo"))
                ->get(['plancontable.ID']);
            return response()->json($cuentas, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $modelo
     * @param  int $balance
     * @return \Illuminate\Http\Response
     */
    public function treePlanCuenta(Request $request)
    {
        try {
            $cuentasBruto = Cuentacontable::
            join('plancontable', 'IDCuenta', '=', 'cuentacontable.ID')
                ->where('plancontable.IDModelo', $request->input("modelo"))
                ->get(['cuentacontable.ID as data', 'plancontable.ID', DB::raw(" CONCAT(cuentacontable.NumeroCuenta,' ', cuentacontable.Etiqueta) as label"), 'NumeroCuenta as numerocuenta', 'cuentacontable.IDGrupoCuenta', 'cuentacontable.IDDiario as diario', 'IDPadre']);
            $cuentasPadre = $cuentasBruto->where('IDPadre', null);
            $cuentas = $this->to_tree($cuentasPadre, $cuentasBruto);
            return response()->json($cuentas, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }



    /**
     * Remove the specified resource from storage.
     *
     * * @return \Illuminate\Http\Response
     */
    public function PlanCuenta()
    {
        $modelo = 12;
        $cuentasBruto = Cuentacontable::
        join('plancontable', 'IDCuenta', '=', 'cuentacontable.ID')
            ->where('plancontable.IDModelo', $modelo)
            ->get(['cuentacontable.*', 'plancontable.ncuenta']);
        $cuentasPadre = $cuentasBruto->where('IDPadre', null);
        $planCuenta = $this->to_children($cuentasPadre, $cuentasBruto);
        //return response()->json($planCuenta, 200);
        return $planCuenta->toArray();
    }


    public function to_children($parents, $all)
    {
        $array = collect();
        foreach ($parents as $parent) {
            if ($all->contains('IDPadre', $parent["ID"])) {
                $parent["children"] = $this->to_children($all->where('IDPadre', $parent["ID"]), $all);
            }
            $array->push($parent);
        }
        return $array;
    }

    public function to_tree($parents, $all)
    {
        $array = collect();
        foreach ($parents as $parent) {
            if ($all->contains('IDPadre', $parent["data"])) {
                $parent["children"] = $this->to_tree($all->where('IDPadre', $parent["data"]), $all);
            }
            $array->push($parent);
        }
        return $array;
    }
}
