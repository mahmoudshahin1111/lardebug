<?php

namespace LarDebug;

use Closure;
use Exception;
use Illuminate\Http\Request;
use LarDebug\Server as LarDebugServer;
use LarDebug\Collectors\ExceptionCollector;
use Illuminate\Foundation\Exceptions\Handler;

class Middleware
{
    private $larDebug;
    public function handle(Request $request, Closure $next)
    {

        $larDebug = app(LarDebug::class);
        $server = app(LarDebugServer::class);

        $server->sendStartSignal();
        $response = $next($request);
        if (isset($response->exception)) {
            $exceptionCollector = app(ExceptionCollector::class);
            $exceptionHandler = app(Handler::class);
            $renderedExceptionHtml =  $exceptionHandler->render($request, $response->exception)->getContent();
            $exceptionCollector->add($response->exception, $renderedExceptionHtml);
        }

        $larDebug->sendCollectToServer();

        $server->sendEndSignal();

        return $response;
    }
    public function terminate($request, $response)
    {
    }
}
