<?php

namespace App\Http\Controllers;

use App\Models\Cuentacontable;
use App\Models\Modeloplancontable;
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
                $planc = DB::select('SELECT fn_Sel_PlanContable(?,?) data;', [0, $request->input('id')]);
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function apiPlanCuenta()
    {
        try {
            $modelos = Modeloplancontable::where('Estado', 'ACT')->get();
            foreach ($modelos as $modelo) {
                $cuentasBruto = Cuentacontable::
                    join('plancontable', 'IDCuenta', '=', 'cuentacontable.ID')
                    ->where('plancontable.IDModelo', $modelo['ID'])
                    ->get(['cuentacontable.ID', 'Etiqueta', 'NumeroCuenta', 'cuentacontable.Estado', 'IDPadre']);
                $cuentasPadre = $cuentasBruto->where('IDPadre', null);
                $modelo["cuentas"] = $this->to_tree($cuentasPadre, $cuentasBruto);
            }
            return response()->json($modelos, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    public function to_tree($parents, $all)
    {
        $array = collect();
        foreach ($parents as $parent) {
            if ($all->contains('IDPadre', $parent["ID"])) {
                $parent["children"] = $this->to_tree($all->where('IDPadre', $parent["ID"]), $all);
            }
            $array->push($parent);
        }
        return $array;
    }
}