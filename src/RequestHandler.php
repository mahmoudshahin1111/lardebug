<?php

namespace LarDebug;

use Illuminate\Http\Request;
use LarDebug\Server;
use LarDebug\ServerPayload;
class RequestHandler
{
    private $server;
    private $collectors;
    private $exceptionHandler;
    /**
     * Undocumented function
     *
     * @param Server $server
     * @param array  $collectors
     */
    public function __construct($server,$exceptionHandler)
    {
        $this->server = $server;
        $this->exceptionHandler = $exceptionHandler;
        $this->collectors = [];
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return Request
     */
    public function handleRequest($request)
    {
        $this->server->sendPayload(new ServerPayload('start',[]));
        return $request;
    }
    public function handleResponse($response)
    {
        $payload = new ServerPayload('collect',[
            'route' => $this->collectors['route']->collect(),
            'request' => $this->collectors['request']->collect(),
            'queries' => $this->collectors['query']->collect(),
            'messages' => $this->collectors['message']->collect(),
            'exceptions' => $this->collectors['exceptions']->collect(),
        ]);
        $this->server->sendPayload($payload);
        $this->server->sendPayload(new ServerPayload('end',[]));
        return $response;
    }
    public function addCollector($key, $collector)
    {
        $this->collectors = array_merge($this->collectors, [$key => $collector]);
    }
    public function handleException($request,$exception){
        $renderedHtml = $this->exceptionHandler->render($request, $exception)->getContent();
        $this->collectors['exceptions']->add($exception, $renderedHtml);
    }

}
