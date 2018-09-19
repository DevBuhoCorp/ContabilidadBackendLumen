<?php

namespace App\Http\Controllers;

use App\Models\Diariocontable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiarioContableController extends Controller
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
                $diarios = DB::table('diariocontable')
                    ->join('naturaleza', 'diariocontable.IDNaturaleza', '=', 'naturaleza.ID')
                    ->select(DB::raw('diariocontable.ID,diariocontable.Codigo,diariocontable.Etiqueta,naturaleza.Etiqueta as Naturaleza,diariocontable.Estado,naturaleza.ID as IDNaturaleza'))
                    ->paginate($request->input('psize'));
                return response()->json($diarios, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    public function combo()
    {
        try {
            $diarios = DiarioContable::all();
            return response()->json($diarios, 200);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            if ($request->isJson()) {
                $diario = Diariocontable::create($request->all());
                $diario->Estado = $diario->Estado ? 'ACT' : 'INA';
                $diario->save();
                return response()->json($diario, 201);
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
        try
        {
            $diarios = DB::table('diariocontable')
                ->join('naturaleza', 'diariocontable.IDNaturaleza', '=', 'naturaleza.ID')
                ->select(DB::raw('diariocontable.ID,diariocontable.Codigo,diariocontable.Etiqueta,diariocontable.Etiqueta as Naturaleza,diariocontable.Estado,naturaleza.ID as IDNaturaleza'))
                ->where('diariocontable.ID', '=', $id)
                ->get();
            return response()->json($diarios, 200);
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
        try
        {
            if ($request->isJson()) {
                $diarios = Diariocontable::find($id);
                $diarios->Codigo = $request->input('Codigo');
                $diarios->Etiqueta = $request->input('Etiqueta');
                $diarios->IDNaturaleza = $request->input('IDNaturaleza');
                $diarios->Estado = $request->input('Estado') ? 'ACT' : 'INA';
                $diarios->save();
                return response()->json($diarios, 201);
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
    public function destroy($request)
    {
        try
        {
            $diarios = Diariocontable::find($request);
            $diarios->Estado = 'INA';
            $diarios->save();
            return response()->json($diarios, 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }
}
