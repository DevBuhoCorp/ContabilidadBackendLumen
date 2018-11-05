<?php

namespace App\Http\Controllers;

use App\Models\Cuentacontable;
use \App\Http\Controllers\TransaccionController;
use App\Models\Cuentabalance;
use App\Models\Parametroempresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportEstadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function estado_resultado(Request $request, $empresa)
    {
        $parametro = Parametroempresa::where('IDEmpresa', $empresa )->first();

        $resultado = Cuentacontable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
            ->join('modeloplancontable', 'plancontable.IDModelo', '=', 'modeloplancontable.ID')
            ->where('modeloplancontable.ID', $parametro->Valor )
            ->where('cuentacontable.IDTipoEstado', 1)
            ->where('cuentacontable.IDGrupoCuenta', 2)
            ->where('cuentacontable.Saldo', '!=', 0)
            ->groupBy('cuentacontable.ID')
            ->orderBy('cuentacontable.NumeroCuenta')
            ->get(['cuentacontable.ID', 'cuentacontable.Saldo',DB::raw("CONCAT(cuentacontable.NumeroCuenta,' ', cuentacontable.Etiqueta) Etiqueta"),  DB::raw("IF(cuentacontable.Saldo > 0, cuentacontable.Saldo, 0) Deudor"),DB::raw("IF(cuentacontable.Saldo < 0, ABS(cuentacontable.Saldo), 0) Acreedor")]);
        $utilidades = $resultado->sum('Saldo') * -1;
        $participacion = $utilidades * 0.15;
        $uimpuesto = $utilidades - $participacion;
        $impuesto = $uimpuesto * 0.25;
        $ugravable = $uimpuesto - $impuesto;
        $reserva = $ugravable * 0.10;
        $uneta = $ugravable - $reserva;
        $resultado2 = [
            ["Etiqueta" => "(=) Utilidad del Ejercicio", "valor" => $utilidades],
            ["Etiqueta" => "(-) Participación a Trabajadores 15%", "valor" => $participacion],
            ["Etiqueta" => "(=) UTILIDAD ANTE EL IMPUESTO ", "valor" => $uimpuesto],
            ["Etiqueta" => "(-) IMPUESTO 25%", "valor" => $impuesto],
            ["Etiqueta" => "(=) UTILIDAD GRAVABLE", "valor" => $ugravable],
            ["Etiqueta" => "(-) Reserva Legal 10%", "valor" => $reserva],
            ["Etiqueta" => "(=) Utlidad Neta", "valor" => $uneta]
        ];
        $utilidadesupd = CuentaContable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
            ->where('plancontable.IDModelo', $parametro->Valor)
            ->where('cuentacontable.NumeroCuenta', '3.4')->get(['cuentacontable.ID','cuentacontable.NumeroCuenta','cuentacontable.Etiqueta','cuentacontable.IDGrupoCuenta','cuentacontable.IDPadre','cuentacontable.Estado','cuentacontable.Saldo','cuentacontable.IDDiario','cuentacontable.IDTipoEstado'])[0];   
        $utilidadesupd->Saldo = $utilidades * -1;
        $utilidadesupd->save();
        $participacionupd = CuentaContable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
            ->where('plancontable.IDModelo', $parametro->Valor)
            ->where('cuentacontable.NumeroCuenta', '2.1.2')->get(['cuentacontable.ID','cuentacontable.NumeroCuenta','cuentacontable.Etiqueta','cuentacontable.IDGrupoCuenta','cuentacontable.IDPadre','cuentacontable.Estado','cuentacontable.Saldo','cuentacontable.IDDiario','cuentacontable.IDTipoEstado'])[0];   
        $participacionupd->Saldo = $participacion * -1;
        $participacionupd->save();
        $impuestoupd = CuentaContable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
        ->where('plancontable.IDModelo', $parametro->Valor)
        ->where('cuentacontable.NumeroCuenta', '2.1.4')->get(['cuentacontable.ID','cuentacontable.NumeroCuenta','cuentacontable.Etiqueta','cuentacontable.IDGrupoCuenta','cuentacontable.IDPadre','cuentacontable.Estado','cuentacontable.Saldo','cuentacontable.IDDiario','cuentacontable.IDTipoEstado'])[0];   
        $impuestoupd->Saldo = $impuesto * -1;
        $impuestoupd->save();
        $reservaupd = CuentaContable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
        ->where('plancontable.IDModelo', $parametro->Valor)
        ->where('cuentacontable.NumeroCuenta', '3.2')->get(['cuentacontable.ID','cuentacontable.NumeroCuenta','cuentacontable.Etiqueta','cuentacontable.IDGrupoCuenta','cuentacontable.IDPadre','cuentacontable.Estado','cuentacontable.Saldo','cuentacontable.IDDiario','cuentacontable.IDTipoEstado'])[0];   
        $reservaupd->Saldo = $reserva * -1;
        $reservaupd->save();
        $utilidadupd = CuentaContable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
        ->where('plancontable.IDModelo', $parametro->Valor)
        ->where('cuentacontable.NumeroCuenta', '3.3')->get(['cuentacontable.ID','cuentacontable.NumeroCuenta','cuentacontable.Etiqueta','cuentacontable.IDGrupoCuenta','cuentacontable.IDPadre','cuentacontable.Estado','cuentacontable.Saldo','cuentacontable.IDDiario','cuentacontable.IDTipoEstado'])[0];   
        $utilidadupd->Saldo = $uneta * -1;
        $utilidadupd->save();
        return response()->json(['resultado' => $resultado, 'resultado2' => $resultado2], 201);
    }

    public function balancefinal( Request $request ){
        $parametro = Parametroempresa::where('IDEmpresa', $request->input('Empresa') )->first();

        $activos = Cuentabalance::join('plancontable', 'plancontable.ID', '=', 'cuentabalance.IDPlanContable')
        ->join('cuentacontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
        ->where('cuentacontable.NumeroCuenta','like','1%')
        ->where('plancontable.IDModelo', $parametro->Valor )
        ->where('cuentabalance.IDBalance',2)->get(['cuentacontable.Etiqueta',DB::raw('ABS(cuentacontable.Saldo) as Saldo')]);

        $pasivos = Cuentabalance::join('plancontable', 'plancontable.ID', '=', 'cuentabalance.IDPlanContable')
        ->join('cuentacontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
        ->where('cuentacontable.NumeroCuenta','like','2%')
            ->where('plancontable.IDModelo', $parametro->Valor )
        ->where('cuentabalance.IDBalance',2)->get(['cuentacontable.Etiqueta',DB::raw('ABS(cuentacontable.Saldo) as Saldo')]);

        $patrimonio = Cuentabalance::join('plancontable', 'plancontable.ID', '=', 'cuentabalance.IDPlanContable')
        ->join('cuentacontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
        ->where('cuentacontable.NumeroCuenta','like','3%')
            ->where('plancontable.IDModelo', $parametro->Valor )
        ->where('cuentabalance.IDBalance',2)->get(['cuentacontable.Etiqueta',DB::raw('ABS(cuentacontable.Saldo) as Saldo')]);

        return response()->json(['activos' => $activos, 'pasivos' => $pasivos, 'patrimonio' => $patrimonio,
        'sumaactivos' => $activos->sum('Saldo'), 'sumapasivo' => $pasivos->sum('Saldo'),'sumapatrimonio' => $patrimonio->sum('Saldo')], 201);
    }

    public function hojabalance($empresa){
        $comprobacion = TransaccionController::balanceComprobacion($empresa);
        $parametro = Parametroempresa::where('IDEmpresa', $empresa )->first();

        $resultado = Cuentacontable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
            ->join('modeloplancontable', 'plancontable.IDModelo', '=', 'modeloplancontable.ID')
            ->where('modeloplancontable.ID', $parametro->Valor )
            ->where('cuentacontable.IDTipoEstado', 1)
            ->where('cuentacontable.Saldo', '!=', 0)
            ->where('cuentacontable.IDGrupoCuenta', '=', 2)
            ->groupBy('cuentacontable.ID')
            ->orderBy('cuentacontable.NumeroCuenta')
            ->get(['cuentacontable.ID', 'cuentacontable.Saldo',DB::raw("CONCAT(cuentacontable.NumeroCuenta,' ', cuentacontable.Etiqueta) Etiqueta"),  DB::raw("IF(cuentacontable.Saldo > 0, cuentacontable.Saldo, 0) Deudor"),DB::raw("IF(cuentacontable.Saldo < 0, ABS(cuentacontable.Saldo), 0) Acreedor")]);


       $final = Cuentabalance::join('plancontable', 'plancontable.ID', '=', 'cuentabalance.IDPlanContable')
        ->join('cuentacontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
        ->where('cuentabalance.IDBalance',2)->get(['cuentacontable.Etiqueta',DB::raw("IF (cuentacontable.NumeroCuenta LIKE '1%' || cuentacontable.Saldo > 0, ABS(cuentacontable.Saldo), 0) Deudor"),DB::raw("IF (cuentacontable.NumeroCuenta NOT LIKE '1%' && cuentacontable.Saldo < 0, ABS(cuentacontable.Saldo), 0) Acreedor")]);

        $ajuste = Cuentacontable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
        ->where('plancontable.IDModelo', $parametro->Valor)
        ->where('cuentacontable.NumeroCuenta','3.4')->get(['cuentacontable.Saldo'])[0];

        

        $suman = [
            ["Etiqueta" => "Balance de Comprobación", "Debe" => $comprobacion->sum('Deudor'), "Haber" => $comprobacion->sum('Acreedor')],
            ["Etiqueta" => "Estado de Resultados + Utilidad del Ejercicio", "Debe" => $resultado->sum('Deudor') - $ajuste->Saldo, "Haber" => $resultado->sum('Acreedor')],
            ["Etiqueta" => "Balance Final", "Debe" => $final->sum('Deudor'), "Haber" => $final->sum('Acreedor')],
        ];

        return response()->json(['comprobacion' => $comprobacion, 'resultado' => $resultado ,'final' => $final, 'suman' => $suman], 201);
    }
}
