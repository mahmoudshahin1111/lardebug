<?php



namespace LarDebug;

use Illuminate\Http\Request;
use Closure;


class Middleware{
    public function handle (Request $request,Closure $next){
        $larDebug = app(\LarDebug::class);
        $larDebug->sendStartSignal();
        $response =  $next($request);

        if (isset($response->exception)) {
            $larDebug->getCollector('exceptions')->addException($response->exception);
        }
        $larDebug->sendCollectToServer();
        $larDebug->sendEndSignal();
        return $response;
    }
    public function terminate($request, $response)
    {
     
    }
}