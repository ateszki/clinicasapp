<?php

namespace App\Http\Middleware;

use Closure;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	    if (\Input::get('apikey') != \Config::get('app.apikey'))
	    {
		\App::abort(401, 'Ingreso no autorizado.');
	    }
        return $next($request);
    }
}
