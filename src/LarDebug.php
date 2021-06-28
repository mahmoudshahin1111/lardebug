<?php

namespace LarDebug;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Application as App;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Lib\Collectors\MessageCollector;
use Lib\Collectors\QueryCollector;
use Lib\Collectors\RequestCollector;
use Lib\Collectors\RouteCollector;

class LarDebug
{
    protected $server;
    protected $collectors;
    public function __construct(App $app,Server $server,$collectors)
    {
        if (!isset($app)) {
            $this->app = app();
        }
        $this->server = $server;
        $this->collectors = $collectors;
    }

    public function addMessage($body)
    {
        $this->collectors['message']->addMessage($body);
    }
    public function addConsoleMessage($body){
        $this->server->sendPayload('console',[
            'id' => \uniqid('message_'),
            'time' => \microtime(true) * 1000,
            'body' => $body,

        ]);
    }
    public function sendStartSignal()
    {
        $this->server->sendPayload('start', []);
    }
    public function sendCollectToServer()
    {
        $this->server->sendPayload('collect', [
            'route' => $this->collectors['route']->collect(),
            'request' =>$this->collectors['request']->collect(),
            'queries' => $this->collectors['query']->collect(),
            'messages' => $this->collectors['message']->collect(),
            'events' => $this->collectors['events']->collect(),
        ]);
    }
    public function sendEndSignal()
    {
        $this->server->sendPayload('end', []);
    }
}
