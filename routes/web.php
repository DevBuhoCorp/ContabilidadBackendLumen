<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/key', function () {
    return str_random(32);
});
//Catálogo Empresa
$router->get('empresa', ['uses' => 'EmpresaController@index']);
$router->get('empresa/{id}', ['uses' => 'EmpresaController@show']);
$router->get('comboempresa', ['uses' => 'EmpresaController@combo']);
$router->post('empresa', ['uses' => 'EmpresaController@store']);
$router->put('empresa/{id}', ['uses' => 'EmpresaController@update']);
$router->delete('empresa/{id}', ['uses' => 'EmpresaController@destroy']);

//Catálogo Aplicación
$router->get('aplicacion', ['uses' => 'AplicacionController@index']);
$router->get('aplicacion/{id}', ['uses' => 'AplicacionController@show']);
$router->post('aplicacion', ['uses' => 'AplicacionController@store']);
$router->put('aplicacion/{id}', ['uses' => 'AplicacionController@update']);
$router->delete('aplicacion/{id}', ['uses' => 'AplicacionController@destroy']);

//Catálogo Diario Contable
$router->get('diarios', ['uses' => 'DiarioContableController@index']);
$router->get('diarios/{id}', ['uses' => 'DiarioContableController@show']);
$router->get('combodiario', ['uses' => 'DiarioContableController@combo']);
$router->post('diarios', ['uses' => 'DiarioContableController@store']);
$router->put('diarios/{id}', ['uses' => 'DiarioContableController@update']);
$router->delete('diarios/{id}', ['uses' => 'DiarioContableController@destroy']);

//Naturaleza
$router->get('naturaleza', ['uses' => 'NaturalezaController@index']);

//Catálogo Modelo Plan Contable
$router->get('modeloplancontable', ['uses' => 'ModeloPlanContableController@index']);
$router->get('modeloplancontable/{id}', ['uses' => 'ModeloPlanContableController@show']);
$router->get('combomodelo', ['uses' => 'ModeloPlanContableController@combo']);
$router->post('modeloplancontable', ['uses' => 'ModeloPlanContableController@store']);
$router->put('modeloplancontable/{id}', ['uses' => 'ModeloPlanContableController@update']);
$router->delete('modeloplancontable/{id}', ['uses' => 'ModeloPlanContableController@destroy']);

//PlanContable
$router->get('plancontable', ['uses' => 'PlanContableController@index']);
$router->get('numerocuenta', ['uses' => 'PlanContableController@numerocuenta']);
$router->get('app/plancontable/cuentacontable', ['uses' => 'PlanContableController@apiPlanCuenta']);

//CuentaContable
$router->get('cuentacontable', ['uses' => 'CuentaContableController@index']);
$router->get('cuentacontable/{id}', ['uses' => 'CuentaContableController@show']);
$router->post('cuentacontable', ['uses' => 'CuentaContableController@store']);
$router->put('cuentacontable/{id}', ['uses' => 'CuentaContableController@update']);
$router->get('autocomplete', ['uses' => 'CuentaContableController@autocomplete']);
$router->get('dragcuentacontable', ['uses' => 'CuentaContableController@drag']);
$router->get('cuentapadre', ['uses' => 'CuentaContableController@MaxPadre']);
$router->get('plancontable/{pc}/cuentacontable/{id}', ['uses' => 'CuentaContableController@getNumCuenta']);
