<?php



namespace LarDebug;

use Illuminate\Http\Request;
use Closure;
use Exception;
use LarDebug\Collectors\ExceptionCollector;
use LarDebug\Server as LarDebugServer;

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
            $exceptionCollector->addException($response->exception);
        }
      
        $larDebug->sendCollectToServer();
    
        $server->sendEndSignal();
 
        return $response;
    }
    public function terminate($request, $response)
    {
    }
}
