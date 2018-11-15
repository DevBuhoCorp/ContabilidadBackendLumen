<?php

namespace App\Http\Controllers;

use App\Models\Cuentacontable;
use App\Models\Empresa;
use App\Models\Modeloplancontable;
use App\Models\Parametroempresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function apiEmpresa(  )
    {
        try {
                $empresa = Empresa::where('Estado', 'ACT')->get([ 'ID', 'RazonSocial AS Nombre' ]);
                return response()->json($empresa, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int $Empresa
     * @return \Illuminate\Http\Response
     */
    public function apiPlanCuenta( $Empresa )
    {
        try {
            $Parametroempresa = Parametroempresa::where('IDEmpresa', $Empresa)->where('Abr', 'PCH')->first();
            $modelos = Modeloplancontable::where('Estado', 'ACT')->where('IDEmpresa', $Empresa)->get(['Modeloplancontable.*', DB::raw( 'ID = '. $Parametroempresa->Valor. ' as Habilitado ') ]);
            foreach ($modelos as $modelo) {
                $cuentasBruto = Cuentacontable::
                join('plancontable', 'IDCuenta', '=', 'cuentacontable.ID')
                    ->where('plancontable.IDModelo', $modelo['ID'])
                    ->get(['cuentacontable.ID', 'Etiqueta', 'NumeroCuenta', 'cuentacontable.Estado', 'IDPadre']);
                $modelo["PlanCuenta"] = $cuentasBruto;

//                $cuentasPadre = $cuentasBruto->where('IDPadre', null);
//                $modelo["cuentas"] = $this->to_children($cuentasPadre, $cuentasBruto);
            }
            return response()->json($modelos, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $Empresa
     * @return \Illuminate\Http\Response
     */
    public function apiPlanHabCuenta( $Empresa )
    {
        try {
            $Parametroempresa = Parametroempresa::where('IDEmpresa', $Empresa)->where('Abr', 'PCH')->first();

            $modelo = Modeloplancontable::find($Parametroempresa->Valor);

            $cuentasBruto = Cuentacontable::
                            join('plancontable', 'IDCuenta', '=', 'cuentacontable.ID')
                            ->where('plancontable.IDModelo', $modelo['ID'])
                            ->get(['plancontable.ID', 'Etiqueta', 'NumeroCuenta', 'cuentacontable.Estado']);
//                            ->get(['cuentacontable.ID', 'Etiqueta', 'NumeroCuenta', 'cuentacontable.Estado', 'IDPadre']);

            $modelo["PlanCuenta"] = $cuentasBruto;

//            $cuentasPadre = $cuentasBruto->where('IDPadre', null);
//            $modelo["PlanCuenta"] = $this->to_children($cuentasPadre, $cuentasBruto);
            return response()->json($modelo, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }




    private function to_children($parents, $all)
    {
        $array = collect();
        foreach ($parents as $parent) {
            if ($all->contains('IDPadre', $parent["ID"])) {
                $parent["children"] = $this->to_children($all->where('IDPadre', $parent["ID"]), $all);
            }
            $array->push($parent);
        }
        return $array;
    }

}
