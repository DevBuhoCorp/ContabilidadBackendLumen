<?php

namespace App\Http\Middleware;

use App\Models\Estacion;
use Closure;

class AppTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($request->header('AppAuth')) {

            if ( !Estacion::where('Estado', 'ACT')->where('Token', $request->header('AppAuth'))->first() ) {
                return response('Unauthorized.', 401);
            }
            $request["estacion"] = Estacion::where('Token', $request->header('AppAuth'))->first([ 'ID', 'NMaquina', 'IDAplicacion' ]);
            $response = $next($request);
            // Post-Middleware Action
            return $response;
        }
        return response('Sin Header.', 401);

    }
}
