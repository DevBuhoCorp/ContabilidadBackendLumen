<?php

namespace App\Http\Controllers;

use App\Models\Tipocuentabancarium;
use Illuminate\Http\Request;

class TipoCuentaBancariaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tcbancaria = Tipocuentabancarium::paginate($request->input('psize'));
        return Response($tcbancaria, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function _Combo(Request $request)
    {
        $tcbancaria = Tipocuentabancarium::where('Estado','ACT')->get();
        return Response($tcbancaria, 200);
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
        $tcbancaria = new Tipocuentabancarium($request->all());
        $tcbancaria->Estado = $request->input("Estado") ? 'ACT' : 'INA';
        $tcbancaria->save();
        return Response($tcbancaria, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tcbancaria = Tipocuentabancarium::find($id);
        return Response($tcbancaria, 200);
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
        $tcbancaria = Tipocuentabancarium::find($id);
        $tcbancaria->Descripcion = $request->input('Descripcion');
        $tcbancaria->Observacion = $request->input('Observacion');
        $tcbancaria->Estado = $request->input("Estado") ? 'ACT' : 'INA';
        $tcbancaria->save();
        return Response($tcbancaria, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tcbancaria = Tipocuentabancarium::find($id);
        $tcbancaria->Estado = 'INA';
        $tcbancaria->save();
        return Response($tcbancaria, 201);
    }
}
