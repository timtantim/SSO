<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
        $allowedOrigins = ['https://192.168.39.73:8885','https://192.168.39.73:8886', 'http://192.168.39.73:8885','http://192.168.39.73:8886','http://login.com','http://192.168.39.106','http://192.168.39.108','http://192.168.39.86','https://192.168.88.247','https://172.16.1.45','https://172.16.1.45:8885'];
        if($request->server('HTTP_ORIGIN')){
            if (in_array($request->server('HTTP_ORIGIN'), $allowedOrigins)) {
                return $next($request)
                    ->header('Access-Control-Allow-Origin', $request->server('HTTP_ORIGIN'))
                    ->header('Access-Control-Allow-Origin', '*')
                    ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
                    ->header('Access-Control-Allow-Headers', '*');
            }
          }
    
        return $next($request);
        // return $next($request);
    }
}
