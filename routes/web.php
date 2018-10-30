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

/* Usuario */
$router->get('usuario', ['uses' => 'UsuarioController@index']);
$router->get('usuario/{id}', ['uses' => 'UsuarioController@show']);
$router->post('usuario', ['uses' => 'UsuarioController@store']);
$router->put('usuario/{id}', ['uses' => 'UsuarioController@update']);
$router->delete('usuario/{id}', ['uses' => 'UsuarioController@destroy']);

/* Rol */
$router->get('rol', ['uses' => 'RolController@index']);
$router->get('rol/{id}', ['uses' => 'RolController@show']);
$router->get('rol_combo', ['uses' => 'RolController@combo']);
$router->post('rol', ['uses' => 'RolController@store']);
$router->put('rol/{id}', ['uses' => 'RolController@update']);
$router->delete('rol/{id}', ['uses' => 'RolController@destroy']);

//Catálogo Empresa
$router->get('empresa', ['uses' => 'EmpresaController@index']);
$router->get('empresa/{id}', ['uses' => 'EmpresaController@show']);
$router->get('comboempresa', ['uses' => 'EmpresaController@combo']);
$router->post('empresa', ['uses' => 'EmpresaController@store']);
$router->put('empresa/{id}', ['uses' => 'EmpresaController@update']);
$router->delete('empresa/{id}', ['uses' => 'EmpresaController@destroy']);

//Catálogo Aplicación
$router->get('aplicacion', ['uses' => 'AplicacionController@index']);
$router->get('comboaplicacion', ['uses' => 'AplicacionController@combo']);
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
$router->get('modeloplancontable/habilitar/{id}', ['uses' => 'ModeloPlanContableController@habilitar']);
$router->post('modeloplancontable', ['uses' => 'ModeloPlanContableController@store']);
$router->put('modeloplancontable/{id}', ['uses' => 'ModeloPlanContableController@update']);
$router->delete('modeloplancontable/{id}', ['uses' => 'ModeloPlanContableController@destroy']);

//PlanContable
$router->get('plancontable', ['uses' => 'PlanContableController@index']);
$router->get('numerocuenta', ['uses' => 'PlanContableController@numerocuenta']);
$router->get('app/plancontable/cuentacontable', ['uses' => 'PlanContableController@apiPlanCuenta']);
$router->get('plancontable/tree', ['uses' => 'PlanContableController@treePlanCuenta']);
$router->get('plancontable/cuentabalance', ['uses' => 'PlanContableController@Modelo_Balance_PlanCuenta']);

//CuentaContable
$router->get('cuentacontable', ['uses' => 'CuentaContableController@index']);
$router->get('cuentacontable/{id}', ['uses' => 'CuentaContableController@show']);
$router->post('cuentacontable', ['uses' => 'CuentaContableController@store']);
$router->put('cuentacontable/{id}', ['uses' => 'CuentaContableController@update']);
$router->get('autocomplete', ['uses' => 'CuentaContableController@autocomplete']);
$router->get('dragcuentacontable', ['uses' => 'CuentaContableController@drag']);
$router->get('cuentapadre', ['uses' => 'CuentaContableController@MaxPadre']);
$router->get('plancontable/{pc}/cuentacontable/{id}', ['uses' => 'CuentaContableController@getNumCuenta']);

//Estacion
$router->get('estacion', ['uses' => 'EstacionController@index']);
$router->get('estacion/{id}', ['uses' => 'EstacionController@show']);
$router->post('estacion', ['uses' => 'EstacionController@store']);
$router->put('estacion/{id}', ['uses' => 'EstacionController@update']);
$router->delete('estacion/{id}', ['uses' => 'EstacionController@destroy']);

//Banco
$router->get('banco', ['uses' => 'BancoController@index']);
$router->get('banco/combo', ['uses' => 'BancoController@_Combo']);
$router->get('banco/{id}', ['uses' => 'BancoController@show']);
$router->post('banco', ['uses' => 'BancoController@store']);
$router->put('banco/{id}', ['uses' => 'BancoController@update']);
$router->delete('banco/{id}', ['uses' => 'BancoController@destroy']);

//Tipo Cuenta Bancaria
$router->get('tipocuentabancaria', ['uses' => 'TipoCuentaBancariaController@index']);
$router->get('tipocuentabancaria/combo', ['uses' => 'TipoCuentaBancariaController@_Combo']);
$router->get('tipocuentabancaria/{id}', ['uses' => 'TipoCuentaBancariaController@show']);
$router->post('tipocuentabancaria', ['uses' => 'TipoCuentaBancariaController@store']);
$router->put('tipocuentabancaria/{id}', ['uses' => 'TipoCuentaBancariaController@update']);
$router->delete('tipocuentabancaria/{id}', ['uses' => 'TipoCuentaBancariaController@destroy']);

//Tipo Cuenta Bancaria
$router->get('cuentabancaria', ['uses' => 'CuentaBancariaController@index']);
//$router->get('cuentabancaria/combo', ['uses' => 'CuentaBancariaController@_Combo']);
$router->get('cuentabancaria/{id}', ['uses' => 'CuentaBancariaController@show']);
$router->post('cuentabancaria', ['uses' => 'CuentaBancariaController@store']);
$router->put('cuentabancaria/{id}', ['uses' => 'CuentaBancariaController@update']);
$router->delete('cuentabancaria/{id}', ['uses' => 'CuentaBancariaController@destroy']);

//Transacciones
$router->get('transaccion', ['uses' => 'TransaccionController@index']);
$router->post('transaccion', ['uses' => 'TransaccionController@store']);
$router->get('transaccion/{id}', ['uses' => 'TransaccionController@show']);
$router->get('transporcuenta/{id}', ['uses' => 'TransaccionController@transporcuenta']);
$router->get('totaltrans', ['uses' => 'TransaccionController@total']);
$router->put('transaccion/{id}', ['uses' => 'TransaccionController@update']);

// Balances Comprobacion
$router->get('balance_comprobacion/{modplanc}', ['uses' => 'TransaccionController@balanceComprobacion']);
$router->get('estadoresultado/{modplanc}', ['uses' => 'TransaccionController@estadoresultado']);
$router->get('combobalance', ['uses' => 'TipoBalanceController@combo']);

//TiposEstado
$router->get('combotipoestado', ['uses' => 'TipoEstadoController@combo']);

/* Reportes */
$router->get('report_estadoresultado/{modplancontable}', ['uses' => 'ReportEstadoController@estado_resultado']);
$router->get('report_balancefinal', ['uses' => 'ReportEstadoController@balancefinal']);
$router->get('hojabalance/{modplanc}', ['uses' => 'ReportEstadoController@hojabalance']);

/* Cuenta Balance */
$router->post('cuentabalance/{modelo}/{balance}', ['uses' => 'TipoBalanceController@store']);

//Exportaciones
$router->get('export_librodiario', ['uses' => 'ExportController@exportlibrodiario']);
$router->get('export_detalletrans/{id}', ['uses' => 'ExportController@exportdetalletrans']);
$router->get('export_libromayor/{id}', ['uses' => 'ExportController@exportlibromayor']);
$router->get('export_balancefinal', ['uses' => 'ExportController@exportbalancefinal']);
$router->get('export_estadoresultado/{id}', ['uses' => 'ExportController@exportestadoresultado']);

//Dashboard
$router->get('transpormes/{empresa}', ['uses' => 'DashboardController@transpormes']);
$router->get('topcuentas/{modelo}', ['uses' => 'DashboardController@topcuentas']);