<?php

namespace LarDebug;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Application as App;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Lib\Collectors\MessageCollector;
use Lib\Collectors\QueryCollector;
use Lib\Collectors\RequestCollector;
use LarDebug\ServerConfigManager;
use LarDebug\EventHandlers\QueueEventHandler;

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
    /**
     * Undocumented variable
     *
     * @var ServerConfigManager
     */
    protected $serverConfigManager;
    /**
     * Undocumented variable
     *
     * @var QueueEventHandler
     */
    protected $queueEventHandler;

    public function __construct(array $collectors,Server $server = null, App $app= null, $serverConfigManager=null)
    {
        $this->app = isset($app)?$app:app();
        $this->collectors = $collectors;
        $this->server = isset($server)?$server:$this->app->make(Server::class);
        $this->serverConfigManager = isset($serverConfigManager)?$serverConfigManager:app(ServerConfigManager::class);
        $this->queueEventHandler = isset($queueEventHandler)?$queueEventHandler:app(QueueEventHandler::class);
    }
    public function bootstrap()
    {
        $this->queueEventHandler->listen();
    }
    public function addMessage($body)
    {
        $this->collectors['message']->addMessage($body);
    }
    public function addConsoleMessage($body)
    {
        $this->server->sendPayload('console', [
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
            'exceptions' => $this->collectors['exceptions']->collect(),
        ]);
    }
    public function sendEndSignal()
    {
        $this->server->sendPayload('end', []);
    }
    public function getCollector($key){
        return $this->collectors[$key];
    }
}
