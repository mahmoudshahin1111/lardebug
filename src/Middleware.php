<?php

namespace LarDebug;

use Closure;
use Illuminate\Http\Request;
use LarDebug\RequestHandler;

class Middleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($this->isLarDebugDisabled()) {
            return $next($request);
        }
        $requestHandler = app(RequestHandler::class);
        $requestHandler->handleRequest($request);
    
        $response = $next($request);
        if (isset($response->exception)) {
            $requestHandler->handleException($request,$response->exception);
        }
    
        $response = $requestHandler->handleResponse($response);
        return $response;
    }
    private function isLarDebugDisabled(){
        return !\config('app.debug') || !\config('lardebug.enabled');
    }
    public function terminate($request, $response)
    {
    }
}
