<?php

namespace App\Http\Controllers;

use App\Models\Cuentabancarium;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CuentaBancariaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $CuentaBancaria = Cuentabancarium::
                        join('Banco','Banco.ID','=' ,'IDBanco')
                        ->join('TipoCuentaBancaria','TipoCuentaBancaria.ID','=' ,'IDTipoCuenta')
                        ->where('IDEmpresa', $request->input('empresa') )
                        ->select('CuentaBancaria.*','Banco.Descripcion as Banco', 'TipoCuentaBancaria.Descripcion as TipoCuenta')
                        ->paginate($request->input('psize'));
        return Response($CuentaBancaria, 200);
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
        $CuentaBancaria = new Cuentabancarium($request->all());
        $CuentaBancaria->Estado = $request->input("Estado") ? 'ACT' : 'INA';
        $CuentaBancaria->save();
        return Response($CuentaBancaria, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $CuentaBancaria = Cuentabancarium::find($id);
        return Response($CuentaBancaria, 200);
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
        $CuentaBancaria = Cuentabancarium::find($id);
        $CuentaBancaria->fill($request->all());
        $CuentaBancaria->Estado = $request->input("Estado") ? 'ACT' : 'INA';
        $CuentaBancaria->save();
        return Response($CuentaBancaria, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $CuentaBancaria = Cuentabancarium::find($id);
        $CuentaBancaria->Estado = 'INA';
        $CuentaBancaria->save();
        return Response($CuentaBancaria, 201);
    }
}
