<?php

namespace App\Http\Controllers;

use App\Models\Datospersonale;
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
                $user = Datospersonale::join('Users', 'Users.id', '=', 'IDUser')
                    ->join('Rol', 'Rol.id', '=', 'Users.IDRol')
                    ->select('DatosPersonales.*', 'Users.email', 'Users.name', 'Users.IDRol', 'Rol.Descripcion as Rol');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if ($request->isJson()) {
                
                //$usuario = User::create($request->all());
                //$usuario->save();
                $usuario = new User();
                $usuario->name = $request->input("name");
                $usuario->email = $request->input("email");
                $usuario->password = password_hash($request->input("password"), PASSWORD_BCRYPT);
                $usuario->IDRol = $request->input("IDRol");
                $usuario->save();
                $datospersonales = new Datospersonale();
                $datospersonales->Cedula = $request->input("Cedula");
                $datospersonales->NombrePrimer = $request->input("NombrePrimer");
                $datospersonales->NombreSegundo = $request->input("NombreSegundo");
                $datospersonales->ApellidoPaterno = $request->input("ApellidoPaterno");
                $datospersonales->ApellidoMaterno = $request->input("ApellidoMaterno");
                $datospersonales->NumConvencional = $request->input("NumConvencional");
                $datospersonales->NumMovil = $request->input("NumMovil");
                $datospersonales->IDUser = $usuario->id;
                $datospersonales->Estado = $request->input("Estado") ? 'ACT' : 'INA';
                $datospersonales->save();
                return response()->json($datospersonales, 201);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Datospersonale::join('Users', 'Users.id', '=', 'IDUser')
            ->join('Rol', 'Rol.id', '=', 'Users.IDRol')
            ->where('DatosPersonales.IDUser', '=', $id)
            ->select('DatosPersonales.*', 'Users.email', 'Users.name', 'Users.IDRol', 'Rol.Descripcion as Rol')->get();
        return response()->json($user, 200);
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
    public function update(Request $request, $userid, $datosid)
    {
        $usuario = User::find($userid);
        $usuario->name = $request->input("name");
        $usuario->email = $request->input("email");
        $usuario->IDRol = $request->input("IDRol");
        $usuario->save();
        $datospersonales = Datospersonale::find($datosid);
        $datospersonales->NombrePrimer = $request->input("NombrePrimer");
        $datospersonales->NombreSegundo = $request->input("NombreSegundo");
        $datospersonales->ApellidoPaterno = $request->input("ApellidoPaterno");
        $datospersonales->ApellidoMaterno = $request->input("ApellidoMaterno");
        $datospersonales->NumConvencional = $request->input("NumConvencional");
        $datospersonales->NumMovil = $request->input("NumMovil");
        $datospersonales->Estado = $request->input("Estado") ? 'ACT' : 'INA';
        $datospersonales->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $datospersonales = Datospersonale::find($id);
            $datospersonales->Estado = 'INA';
            $datospersonales->save();
            return Response($datospersonales, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 500);
        }
    }
}
