<?php

namespace App\Http\Controllers;

use App\Models\Estacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class EstacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $estacions = Estacion::where('IDAplicacion', $request->input('app'))
            ->paginate($request->input('psize'));
        return Response($estacions, 200);
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
        $estacion = new Estacion($request->all());
        $estacion->Estado = $request->input("Estado") ? 'ACT' : 'INA';
        $estacion->Token = Password::getRepository()->createNewToken();
        $estacion->save();
        return Response($estacion, 200);

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
        $estacion = Estacion::find($id);
        $estacion->Nmaquina = $request->input("Nmaquina");
        $estacion->Estado = $request->input("Estado") ? 'ACT' : 'INA';
        $estacion->save();
        return Response($estacion, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $estacion = Estacion::find($id);
        $estacion->Estado = 'INA';
        $estacion->save();
        return Response($estacion, 201);
    }
}
