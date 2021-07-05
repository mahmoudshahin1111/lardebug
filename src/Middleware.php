<?php



namespace LarDebug;

use Illuminate\Http\Request;
use Closure;
use Exception;

class Middleware
{
    private $larDebug;
    public function handle(Request $request, Closure $next)
    {
        $this->larDebug = app(\LarDebug::class);
        $this->larDebug->sendStartSignal();
        $response =  $next($request);
        if ($this->isResponseFails($response)) {
            $this->addToExceptions($response->exception);
        }
        $this->larDebug->sendCollectToServer();
        $this->larDebug->sendEndSignal();
        return $response;
    }
    private function addToExceptions(Exception $exception)
    {
        $this->larDebug->getCollector('exceptions')->addException($exception);
    }
    private function isResponseFails($response)
    {
        return isset($response->exception);
    }
    public function terminate($request, $response)
    {
    }
}
