<?php

namespace App\Http\Controllers;

use App\Models\Datospersonale;


class DatosPersonalesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function show($iduser)
    {
        $datos = Datospersonale::find($iduser);
        return $datos;
    }

    //
}