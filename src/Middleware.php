<?php



namespace LarDebug;

use Illuminate\Http\Request;
use Closure;


class Middleware{
    public function handle (Request $request,Closure $next){
        $larDebug = app('lardebug');
        $larDebug->sendStartSignal();
        $response =  $next($request);
        $larDebug->sendCollectToServer();
        $larDebug->sendEndSignal();
        return $response;
    }
}