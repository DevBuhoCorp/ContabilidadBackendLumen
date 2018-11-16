<?php

namespace App\Http\Controllers;

use App\Models\Cuentacontable;
use App\Models\Modeloplancontable;
use App\Models\Parametro;
use App\Models\Parametroempresa;
use App\Models\Plancontable;
use Illuminate\Http\Request;

class ModeloPlanContableController extends Controller
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
                $modelopc = Modeloplancontable::where('IDEmpresa', $request->input('empresa') );
                $modelopc = $modelopc->paginate($request->input('psize'));
                return response()->json($modelopc, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    public function combo( Request $request )
    {
        try {
            $modelopc = Modeloplancontable::
                        where('Estado', 'ACT')->
                        where('IDEmpresa', $request->input('IDEmpresa'))
                        ->get();
            return response()->json($modelopc, 200);
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
        return null;
    }
    /**
     * Show the form for creating a new resource.
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function habilitar( Request $request , $id)
    {
        try {
            $parametro = Parametroempresa::where('Abr', 'PCH')->where('IDEmpresa', $request->input('Empresa'))->first();
            $parametro->Valor = $id;
            $parametro->save();
            return response()->json( [ "msg" => "Ok"], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
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
                $modelopc = Modeloplancontable::create($request->all());
                $modelopc->Estado = $modelopc->Estado ? 'ACT' : 'INA';
                $modelopc->save();
                $idModelo = Parametro::where('Abr', 'PCP')->first()["Valor"];
                $Plantilla = (new PlanContableController())->PlanCuenta($idModelo);
                $this->PlantillaCuenta_save($Plantilla, $modelopc->ID, null);

                return response()->json($modelopc, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    public function PlantillaCuenta_save($cuentasP, $idMPC, $idRef)
    {
        foreach ($cuentasP as $cP) {
            $cP["IDPadre"] = $idRef;
            $cuenta = new Cuentacontable();
            $cuenta->fill($cP);
            $cuenta->ID = 0;
            $cuenta->save();
            $planc = Plancontable::create(['IDCuenta' => $cuenta->ID, 'IDModelo' => $idMPC, 'ncuenta' => $cP['ncuenta']]);
            if (array_key_exists("children", $cP)) {
                $this->PlantillaCuenta_save($cP["children"]->toArray(), $idMPC, $cuenta->ID);
            }

        }
        return true;
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
            $modelopc = Modeloplancontable::find($id);
            return response()->json($modelopc, 200);
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
        try {
            if ($request->isJson()) {
                $modelopc = Modeloplancontable::find($id);
                $modelopc->fill( $request->all() );
                $modelopc->Estado = $modelopc->Estado ? 'ACT' : 'INA';
                $modelopc->save();
                return response()->json($modelopc, 200);
            }
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
    public function destroy($id)
    {
        try {
            $modelopc = Modeloplancontable::find($id);
            $modelopc->Estado = 'INA';
            $modelopc->save();
            return response()->json($modelopc, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }
}
