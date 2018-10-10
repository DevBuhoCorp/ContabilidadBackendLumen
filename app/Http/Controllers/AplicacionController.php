<?php

namespace App\Http\Controllers;

use App\Models\Aplicacion;
use App\Models\Empresaaplicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AplicacionController extends Controller
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
                $apps = Aplicacion::
                    join('empresaaplicacion as eapp', 'eapp.IDAplicacion', '=', 'aplicacion.ID')
                    ->where('eapp.IDEmpresa', $request->input('empresa'))
                    ->Paginate($request->input('psize'));
                return response()->json($apps, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function combo(Request $request)
    {
        try {
            if ($request->isJson()) {
                $apps = Aplicacion::
                    join('empresaaplicacion as eapp', 'eapp.IDAplicacion', '=', 'aplicacion.ID')
                    ->where('eapp.IDEmpresa', $request->input('empresa'))->get([ 'Aplicacion.ID', 'Aplicacion.Descripcion' ]);
                return response()->json($apps, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listParams(Request $request)
    {
        $op = json_encode($request->all());
        $empresas = DB::select('CALL Sel_Aplicacion (?);', [$op]);
        return Response($empresas, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Response([], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if ($request->isJson()) {
                $app = Aplicacion::create($request->all());
                $app->Estado = $app->Estado ? 'ACT' : 'INA';
                $app->save();

                $EmpApp = new Empresaaplicacion();
                $EmpApp->IDAplicacion = $app->ID;
                $EmpApp->IDEmpresa = $request->input("IDEmpresa");
                $EmpApp->save();
                return response()->json($app, 201);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $app = Aplicacion::find($id);
            return response()->json($app, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Response([], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            if ($request->isJson()) {
                $app = Aplicacion::find($id);
                $app->Estado = $request->input('Estado') ? 'ACT' : 'INA';
                $app->Observacion = $request->input('Observacion');
                $app->Descripcion = $request->input('Descripcion');
                $app->save();
                return response()->json($app, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $app = Aplicacion::find($id);
            $app->Estado = 'INA';
            $app->save();
            return Response($app, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }
}
