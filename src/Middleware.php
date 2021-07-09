<?php



namespace LarDebug;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use LarDebug\Server as LarDebugServer;
use LarDebug\Collectors\ExceptionCollector;

class Middleware
{
    private $larDebug;
    public function handle(Request $request, Closure $next)
    {
       
        $larDebug = app(LarDebug::class);
        $server = app(LarDebugServer::class);
        
        $server->sendStartSignal();
        $response =  $next($request);
        if (isset($response->exception)) {
            $exceptionCollector = app(ExceptionCollector::class);
            $exceptionCollector->add($response->exception);
        }
     
        $larDebug->sendCollectToServer();
    
        $server->sendEndSignal();
 
        return $response;
    }
    public function terminate($request, $response)
    {
    }
}
