<?php

namespace App\Http\Middleware;

use Closure;

class UsuarioAuth
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
	$u = \User::where("session_key","=",\Input::get("session_key"))->where("session_expira",">=",date("Y-m-d H:i:s"))->get();
	if (count($u)==0)
	    {
		\App::abort(401, 'Ud no está autenticado.');
	    }
	\Auth::loginUsingId($u[0]->id);
        return $next($request);
    }
}
