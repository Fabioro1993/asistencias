<?php

namespace App\Http\Middleware;

use Closure;
use Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class CheckDataBase
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
        dd('a');
        $namedb = Request::header('namedb'); // Este es el parámetro a validar
        if(!empty($namedb)){
            \Config::set('database.connections.mysql_dinamica.empresa',$namedb); // Asigno la DB que voy a usar
            DB::connection('mysql_dinamica'); //Asigno la nueva conexión al sistema. 
        }
        return $next($request);
    }
}