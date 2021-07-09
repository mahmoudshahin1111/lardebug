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
use LarDebug\Console\Console;
use LarDebug\Console\Message as ConsoleMessage;

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
    * @var Console
    */
    protected $console;
    public function __construct(Server $server = null, App $app= null)
    {
        $this->collectors = [];
        $this->app = isset($app)?$app:app();
        $this->server = isset($server)?$server:$this->app->make(Server::class);
        $this->serverConfigManager = isset($serverConfigManager)?$serverConfigManager:app(ServerConfigManager::class);
        $this->console = app(Console::class);
    }
    public function addCollector($key, $collector)
    {
        $this->collectors = array_merge($this->collectors, [$key=>$collector]);
    }
    public function consoleLog($body)
    {
        $message= new ConsoleMessage($body);
        $this->console->send($message);
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
  
}
