<?php

namespace App\Http\Controllers;

use App\Models\Datospersonale;
use App\Models\Empresa;
use App\Models\Usersempresa;
use App\User;
use Illuminate\Http\Request;

class UsuarioController extends Controller
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
                $user = Datospersonale::
                join('Users', 'Users.id', '=', 'IDUser')
                    ->join('Rol', 'Rol.id', '=', 'Users.IDRol')
                    ->select('DatosPersonales.*', 'Users.email', 'Rol.Descripcion as Rol');
                $user = $user->paginate($request->input('psize'));
                return response()->json($user, 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Display the specified resource.
     *
     * @param  int $usuario
     * @return \Illuminate\Http\Response
     */
    public function listUsuarioEmpresa($usuario)
    {
        try {
            $empresas = Empresa::join('UsersEmpresa', 'IDEmpresa', '=', 'Empresa.ID')
                ->where('UsersEmpresa.IDUsers', $usuario)
                ->get(['UsersEmpresa.*', 'Empresa.Descripcion']);
            return response()->json($empresas, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $usuario
     * @return \Illuminate\Http\Response
     */
    public function saveUsuarioEmpresa(Request $request, $usuario)
    {
        try {
            //dd($request->all());
            foreach ($request->all() as $row){
                $UsersEmpresa = ( $row["ID"] == 0)? new Usersempresa() : Usersempresa::find($row["ID"]);
                $UsersEmpresa->fill( $row );
                $UsersEmpresa->save();
            }

            return response()->json([], 200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
