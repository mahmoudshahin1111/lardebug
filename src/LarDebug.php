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
    * Undocumented variable
    *
    * @var Console
    */
    protected $console;
    public function __construct(App $app= null)
    {
        $this->app = isset($app)?$app:app();
        $this->console = app(Console::class);
    }

    public function log($body)
    {
        $message= new ConsoleMessage($body);
        $this->console->send($message);
    }
   
  
  
}
