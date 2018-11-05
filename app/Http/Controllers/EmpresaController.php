<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Modeloplancontable;
use App\Models\Parametro;
use App\Models\Parametroempresa;
use App\Models\Usersempresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
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
                $empresa = new Empresa();
                $empresa = $empresa->paginate($request->input('psize'));
                return response()->json($empresa, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
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
    public function combo(Request $request)
    {
        try {
            if ($request->isJson()) {
                $empresa = Empresa::all();
                return response()->json($empresa, 200);
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
                $empresa = Empresa::create($request->all());
                $empresa->Estado = $empresa->Estado ? 'ACT' : 'INA';
                $empresa->save();

                $modelopc = new Modeloplancontable([ "IDEmpresa" => $empresa->ID, "Modelo" => "Modelo por Defecto", "Etiqueta" => "Modelo por Defecto", "Estado" => 'ACT'  ]);
                $modelopc->save();
                Parametroempresa::create([
                    'Descripcion' => 'Plan Contable Habilitado',
                    'IDEmpresa' => $empresa->ID,
                    'Abr' => 'PCH',
                    'Valor' => $modelopc->ID,
                    'Estado' => 'ACT'
                ]);

                $idModelo = Parametro::where('Abr', 'PCP')->first()["Valor"];
                $Plantilla = (new PlanContableController())->PlanCuenta($idModelo);
                (new ModeloPlanContableController())->PlantillaCuenta_save($Plantilla, $modelopc->ID, null);

                return response()->json($empresa, 201);
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

            $empresa = Empresa::find($id);
            return response()->json($empresa, 200);

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
        return Response([], 200);
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
                $empresa = Empresa::find($id);
                $empresa->fill($request->all());
                $empresa->Estado = $request->input('Estado') ? 'ACT' : 'INA';
                $empresa->save();
                return response()->json($empresa, 201);
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

            $empresa = Empresa::find($id);
            $empresa->Estado = 'INA';
            $empresa->save();
            return response()->json($empresa, 201);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }
}
