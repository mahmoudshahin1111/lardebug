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
use LarDebug\ExceptionHandler;

class LarDebug
{
    /**
     * laravel app instance
     *
     * @var App
     */
    protected $app;
    /**
     * server instance  
     *
     * @var Server
     */
    protected $server;
    /**
     * array of available collectors 
     *
     * @var array
     */
    protected $collectors;
    /**
     * Undocumented variable
     *
     * @var ExceptionHandler
     */
    protected $exceptionHandler;


    public function __construct(Server $server,$collectors,App $app= null,$exceptionHandler = null)
    {
        $this->app = isset($app)?$app:app();
        $this->server = $server;
        $this->collectors = $collectors;
        $this->exceptionHandler = isset($exceptionHandler)?$exceptionHandler:$this->app->make('lardebug.exceptionHandler');
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
