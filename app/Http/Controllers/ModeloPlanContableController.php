<?php

namespace App\Http\Controllers;

use App\Models\Modeloplancontable;
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
                $modelopc = new Modeloplancontable();
                $modelopc = $modelopc->paginate($request->input('psize'));
                return response()->json($modelopc, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    public function combo()
    {
        try {
            $modelopc = Modeloplancontable::all();
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if ($request->isJson()) {
                $modelopc = Modeloplancontable::create($request->all());
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
     * Display the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
                $modelopc = Modeloplancontable::find($id);
                $modelopc->modelo = $request->input('Modelo');
                $modelopc->etiqueta = $request->input('Etiqueta');
                $modelopc->estado = $request->input('Estado');
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
     * @param  int  $id
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
