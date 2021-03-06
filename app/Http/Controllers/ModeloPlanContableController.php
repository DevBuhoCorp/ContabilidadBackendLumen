<?php

namespace App\Http\Controllers;

use App\Models\Cuentacontable;
use App\Models\Datospersonale;
use App\Models\Modeloplancontable;
use App\Models\Parametro;
use App\Models\Parametroempresa;
use App\Models\Plancontable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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
                $modelopc = Modeloplancontable::where('IDEmpresa', $request->input('empresa') );
                $modelopc = $modelopc->paginate($request->input('psize'));
                return response()->json($modelopc, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    public function combo( Request $request )
    {
        try {
            $modelopc = Modeloplancontable::
                        where('Estado', 'ACT')->
                        where('IDEmpresa', $request->input('IDEmpresa'))
                        ->get();
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
     * Show the form for creating a new resource.
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function habilitar( Request $request , $id)
    {
        try {
            $parametro = Parametroempresa::where('Abr', 'PCH')->where('IDEmpresa', $request->input('Empresa'))->first();
            $parametro->Valor = $id;
            $parametro->save();
            return response()->json( [ "msg" => "Ok"], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if ($request->isJson()) {
                $data = $request->all();
                $data["Estado"] = $data["Estado"] ? 'ACT' : 'INA';
                $modelopc = Modeloplancontable::create( $data );
                DB::select('call StorePlantilla(:Modelo)', [ "Modelo" => $modelopc->ID ]);

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
     * @param  int $id
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
        try {
            if ($request->isJson()) {
                $modelopc = Modeloplancontable::find($id);
                $modelopc->fill( $request->all() );
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
     * Remove the specified resource from storage.
     *
     * @param  int $id
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

    public function export(Request $request, $modelopc)
    {
        try {
            $data = Cuentacontable::join('plancontable', 'IDCuenta', 'cuentacontable.ID')
                ->where('plancontable.IDModelo', $modelopc)
                ->get(['NumeroCuenta', 'Etiqueta', 'Saldo'])->toArray();

            $data = array_map(function( $row ){
                return [
                    $row["NumeroCuenta"],
                    (str_repeat('        ', substr_count($row["NumeroCuenta"], '.'))) . $row["Etiqueta"],
                    $row["Saldo"]
                ];
            }, $data);



            $user = Datospersonale::where('IDUser', $request->user()->id)->first();

            return Excel::load('app/Files/PlanContable.xlsx', function ($reader) use ($request, $data, $user) {

                $sheet = $reader->getActiveSheet();

                $sheet->setCellValue('A3', 'Fecha Reporte: '. date('Y-m-d H:i:s') );
                $sheet->setCellValue('A4', 'Usuario Reporte: '. $user->ApellidoPaterno. ' ' . $user->NombrePrimer );

                $sheet->fromArray($data, null, 'A6', true);


            })->download('xlsx', [ 'Access-Control-Allow-Origin' => '*' ]);


        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

}
