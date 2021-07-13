<?php

namespace LarDebug;

use Closure;
use Illuminate\Http\Request;
use LarDebug\RequestHandler;

class Middleware
{
    public function handle(Request $request, Closure $next)
    {

        $requestHandler = app(RequestHandler::class);
        $requestHandler->handleRequest($request);
    
        $response = $next($request);
        if (isset($response->exception)) {
            $requestHandler->handleException($request,$response->exception);
        }
    
        $response = $requestHandler->handleResponse($response);
        return $response;
    }
    public function terminate($request, $response)
    {
    }
}
