<?php

namespace App\Http\Controllers;

use App\Models\Cuentacontable;
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
    public function estado_resultado(Request $request, $modplancontable)
    {
        $resultado = Cuentacontable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
            ->join('modeloplancontable', 'plancontable.IDModelo', '=', 'modeloplancontable.ID')
            ->where('modeloplancontable.ID', $modplancontable)
            ->where('cuentacontable.IDTipoEstado', 1)
            ->where('cuentacontable.Saldo', '!=', 0)
            ->groupBy('cuentacontable.ID')
            ->orderBy('cuentacontable.NumeroCuenta')
            ->get(['cuentacontable.ID', DB::raw("CONCAT(cuentacontable.NumeroCuenta,' ', cuentacontable.Etiqueta) Etiqueta"), 'cuentacontable.Saldo']);
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
            ->where('plancontable.IDModelo', $modplancontable)
            ->where('cuentacontable.NumeroCuenta', '2.1.2')->get(['cuentacontable.ID','cuentacontable.NumeroCuenta','cuentacontable.Etiqueta','cuentacontable.IDGrupoCuenta','cuentacontable.IDPadre','cuentacontable.Estado','cuentacontable.Saldo','cuentacontable.IDDiario','cuentacontable.IDTipoEstado'])[0];   
        $utilidadesupd->Saldo = $participacion * -1;
        $utilidadesupd->save();
        $impuestoupd = CuentaContable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
        ->where('plancontable.IDModelo', $modplancontable)
        ->where('cuentacontable.NumeroCuenta', '2.1.4')->get(['cuentacontable.ID','cuentacontable.NumeroCuenta','cuentacontable.Etiqueta','cuentacontable.IDGrupoCuenta','cuentacontable.IDPadre','cuentacontable.Estado','cuentacontable.Saldo','cuentacontable.IDDiario','cuentacontable.IDTipoEstado'])[0];   
        $impuestoupd->Saldo = $impuesto * -1;
        $impuestoupd->save();
        $reservaupd = CuentaContable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
        ->where('plancontable.IDModelo', $modplancontable)
        ->where('cuentacontable.NumeroCuenta', '3.2')->get(['cuentacontable.ID','cuentacontable.NumeroCuenta','cuentacontable.Etiqueta','cuentacontable.IDGrupoCuenta','cuentacontable.IDPadre','cuentacontable.Estado','cuentacontable.Saldo','cuentacontable.IDDiario','cuentacontable.IDTipoEstado'])[0];   
        $reservaupd->Saldo = $reserva * -1;
        $reservaupd->save();
        $reservaupd = CuentaContable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
        ->where('plancontable.IDModelo', $modplancontable)
        ->where('cuentacontable.NumeroCuenta', '3.2')->get(['cuentacontable.ID','cuentacontable.NumeroCuenta','cuentacontable.Etiqueta','cuentacontable.IDGrupoCuenta','cuentacontable.IDPadre','cuentacontable.Estado','cuentacontable.Saldo','cuentacontable.IDDiario','cuentacontable.IDTipoEstado'])[0];   
        $reservaupd->Saldo = $reserva;
        $reservaupd->save();
        $utilidadupd = CuentaContable::join('plancontable', 'plancontable.IDCuenta', '=', 'cuentacontable.ID')
        ->where('plancontable.IDModelo', $modplancontable)
        ->where('cuentacontable.NumeroCuenta', '3.3')->get(['cuentacontable.ID','cuentacontable.NumeroCuenta','cuentacontable.Etiqueta','cuentacontable.IDGrupoCuenta','cuentacontable.IDPadre','cuentacontable.Estado','cuentacontable.Saldo','cuentacontable.IDDiario','cuentacontable.IDTipoEstado'])[0];   
        $utilidadupd->Saldo = $uneta;
        $utilidadupd->save();
        return response()->json(['resultado' => $resultado, 'resultado2' => $resultado2], 201);
    }
}
