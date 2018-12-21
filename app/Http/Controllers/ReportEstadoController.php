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
    public static function estado_resultado($empresa)
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
        $participaciongeneral = $utilidades * 0.1;
        $participacionfamiliar = $utilidades * 0.05;
        $uimpuesto = $utilidades - $participacion;
        $impuesto = $uimpuesto * 0.25;
        $ugravable = $uimpuesto - $impuesto;
        $reserva = $ugravable * 0.10;
        $uneta = $ugravable - $reserva;
        $resultado2 = [
            ["Etiqueta" => "(=) Utilidad del Ejercicio", "valor" => $utilidades],
            ["Etiqueta" => "(-) Participación a Trabajadores - 10% Trabajadores en General", "valor" => $participaciongeneral],
            ["Etiqueta" => "(-) Participación a Trabajadores - 5% Cargas Familiares", "valor" => $participacionfamiliar],
            ["Etiqueta" => "(=) UTILIDAD ANTE EL IMPUESTO ", "valor" => $uimpuesto],
            ["Etiqueta" => "(-) IMPUESTO 25%", "valor" => $impuesto],
            ["Etiqueta" => "(=) UTILIDAD GRAVABLE", "valor" => $ugravable],
            ["Etiqueta" => "(-) Reserva Legal 10%", "valor" => $reserva],
            ["Etiqueta" => "(=) Utlidad Neta", "valor" => $uneta]
        ];
        
        //Actualiza saldo para Participación a Trabajadores - 10% Trabajadores en General
        $participaciongeneralupd = CuentaContable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
            ->where('plancontable.IDModelo', $parametro->Valor)
            ->where('cuentacontable.NumeroCuenta', '2.1.7.8.1')->get(['plancontable.ID as IDPlanContable','cuentacontable.*'])[0];   
        $participaciongeneralupd->Saldo = $participaciongeneral * -1;
        $participaciongeneralupd->save();
        TransaccionController::updateSaldos($participaciongeneralupd->IDPlanContable,$participaciongeneral * -1);
        
        //Actualiza saldo para Participación a Trabajadores - 5% Cargas Familiares
        $participacionfamiliarupd = CuentaContable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
            ->where('plancontable.IDModelo', $parametro->Valor)
            ->where('cuentacontable.NumeroCuenta', '2.1.7.8.2')->get(['plancontable.ID as IDPlanContable','cuentacontable.*'])[0];   
        $participacionfamiliarupd->Saldo = $participacionfamiliar * -1;
        $participacionfamiliarupd->save();
        TransaccionController::updateSaldos($participacionfamiliarupd->IDPlanContable,$participacionfamiliar * -1);

        //Actualiza saldo para IMPUESTO 25% --DUDA
        $impuestoupd = CuentaContable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
        ->where('plancontable.IDModelo', $parametro->Valor)
        ->where('cuentacontable.NumeroCuenta', '2.1.7.5.3')->get(['plancontable.ID as IDPlanContable','cuentacontable.*'])[0];   
        $impuestoupd->Saldo = $impuesto * -1;
        $impuestoupd->save();
        TransaccionController::updateSaldos($impuestoupd->IDPlanContable,$impuesto * -1);

        //Actualiza saldo para Reserva Legal 10%
        $reservaupd = CuentaContable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
        ->where('plancontable.IDModelo', $parametro->Valor)
        ->where('cuentacontable.NumeroCuenta', '3.1.4.1')->get(['plancontable.ID as IDPlanContable','cuentacontable.*'])[0];   
        $reservaupd->Saldo = $reserva * -1;
        $reservaupd->save();
        TransaccionController::updateSaldos($reservaupd->IDPlanContable,$reserva * -1);

        //Actualiza saldo para Utlidad Neta
        $utilidadupd = CuentaContable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
        ->where('plancontable.IDModelo', $parametro->Valor)
        ->where('cuentacontable.NumeroCuenta', '3.1.7.1')->get(['plancontable.ID as IDPlanContable','cuentacontable.*'])[0];   
        $utilidadupd->Saldo = $uneta * -1;
        $utilidadupd->save();
        TransaccionController::updateSaldos($utilidadupd->IDPlanContable,$uneta * -1);

        return ['resultado' => $resultado, 'resultado2' => $resultado2];
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

      /*   $activosup = Cuentacontable::join('plancontable','plancontable.IDCuenta','=','cuentacontable.ID')
        ->where('cuentacontable.NumeroCuenta', '=', '1')
        ->where('plancontable.IDModelo', '=', $parametro->Valor)->first(['cuentacontable.ID']);


        
        $actualizar = Cuentacontable::find($activosup->ID);

        

        $actualizar->Saldo= $activos->sum('Saldo');
        $actualizar->save();

        $pasivosup = Cuentacontable::join('plancontable as pc','pc.IDCuenta','=','cuentacontable.ID')
        ->WhereRaw('cuentacontable.NumeroCuenta = 2 and pc.IDModelo = ?',[$parametro->Valor])->first(['cuentacontable.ID']);
        $actualizar = Cuentacontable::find($pasivosup->ID);
        $actualizar->Saldo= $pasivos->sum('Saldo');
        $actualizar->save();

        $patrimonioup = Cuentacontable::join('plancontable as pc','pc.IDCuenta','=','cuentacontable.ID')
        ->WhereRaw('cuentacontable.NumeroCuenta = 3 and pc.IDModelo = ?',[$parametro->Valor])->first(['cuentacontable.ID']);
        $actualizar = Cuentacontable::find($patrimonioup->ID);
        $actualizar->Saldo= $patrimonio->sum('Saldo');
        $actualizar->save();
 */
        return response()->json(['activos' => $activos, 'pasivos' => $pasivos, 'patrimonio' => $patrimonio,
        'sumaactivos' => $activos->sum('Saldo'), 'sumapasivo' => $pasivos->sum('Saldo'),'sumapatrimonio' => $patrimonio->sum('Saldo')], 201);
    }

    public function hojabalance($empresa){

        try{
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
        catch(\Exception $e){
            return response()->json(['comprobacion' => [], 'resultado' => [] ,'final' => [], 'suman' => []], 201);
        }
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


        
        
    }
}
