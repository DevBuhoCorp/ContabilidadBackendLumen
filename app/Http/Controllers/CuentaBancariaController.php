<?php

namespace App\Http\Controllers;

use App\Models\Cuentabancarium;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
                        ->join('plancontable','cuentabancaria.IDCuentaContable','=' ,'plancontable.ID')
                        ->join('cuentacontable','plancontable.IDCuenta','=' ,'cuentacontable.ID')
                        ->where('IDEmpresa', $request->input('empresa') )
                        ->select('CuentaBancaria.*','Banco.Descripcion as Banco', 'TipoCuentaBancaria.Descripcion as TipoCuenta', DB::raw("CONCAT(cuentacontable.NumeroCuenta,' ',cuentacontable.Etiqueta) as Cuenta"))
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
        
        $CuentaBancaria = new Cuentabancarium();
       // return 1;
        /*$carbon = new Carbon($request->input("FechaApertura"));
        $CuentaBancaria->FechaApertura = $carbon;*/
        $carbon = new Carbon($request->input("FechaApertura"));
        $fechadoc = $carbon->toDateString();
        $CuentaBancaria->DireccionTitular = $request->input("DireccionTitular");
        $CuentaBancaria->Estado = $request->input("Estado") ? 'ACT' : 'INA';
        $CuentaBancaria->FechaApertura = $fechadoc;
        $CuentaBancaria->IDBanco = $request->input("IDBanco");
        $CuentaBancaria->IDCuentaContable = $request->input("IDCuentaContable");
        $CuentaBancaria->IDEmpresa = $request->input("IDEmpresa");
        $CuentaBancaria->IDTipoCuenta = $request->input("IDTipoCuenta");
        $CuentaBancaria->IdentificacionTitular = $request->input("IdentificacionTitular");
        $CuentaBancaria->NombreTitular = $request->input("NombreTitular");
        $CuentaBancaria->NumeroCuenta = $request->input("NumeroCuenta");
        $CuentaBancaria->SaldoInicial = $request->input("SaldoInicial");
        $CuentaBancaria->SaldoMinimo = $request->input("SaldoMinimo");
       
       
       // $CuentaBancaria->fill($request->all());
        
        

        //return($CuentaBancaria);
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

        $CuentaBancaria->DireccionTitular = $request->input("DireccionTitular");
        $CuentaBancaria->Estado = $request->input("Estado") ? 'ACT' : 'INA';

        $carbon = new Carbon($request->input("FechaApertura"));
        $fechadoc = $carbon->toDateString();

        $CuentaBancaria->FechaApertura = $fechadoc;
        $CuentaBancaria->IDBanco = $request->input("IDBanco");
        $CuentaBancaria->IDCuentaContable = $request->input("IDCuentaContable");
        $CuentaBancaria->IDEmpresa = $request->input("IDEmpresa");
        $CuentaBancaria->IDTipoCuenta = $request->input("IDTipoCuenta");
        $CuentaBancaria->IdentificacionTitular = $request->input("IdentificacionTitular");
        $CuentaBancaria->NombreTitular = $request->input("NombreTitular");
        $CuentaBancaria->NumeroCuenta = $request->input("NumeroCuenta");
        $CuentaBancaria->SaldoInicial = $request->input("SaldoInicial");
        $CuentaBancaria->SaldoMinimo = $request->input("SaldoMinimo");

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
