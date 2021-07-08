<?php

namespace LarDebug;

use Illuminate\Foundation\Application as App;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider as Provider;
use LarDebug\Collectors\MessageCollector;
use LarDebug\Collectors\QueryCollector;
use LarDebug\Collectors\RequestCollector;
use LarDebug\Collectors\RouteCollector;
use LarDebug\Collectors\ExceptionCollector;
use LarDebug\Command\StartDebugServer;
use LarDebug\ServerConfigManager;
use Illuminate\Support\Facades\Queue;
use LarDebug\EventHandlers\QueueEventHandler;
use \LarDebug\Console\Console;
use LarDebug\Middleware as LarDebugMiddleware;

class ServiceProvider extends Provider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make(\Illuminate\Contracts\Http\Kernel::class)->pushMiddleware(LarDebugMiddleware::class);
        $this->app->singleton(RouteCollector::class, function () {
            return new RouteCollector($this->app->make(Router::class));
        });
        $this->app->singleton(RequestCollector::class, function () {
            return new RequestCollector($this->app->make(Request::class));
        });
        $this->app->singleton(QueryCollector::class, function () {
            return new QueryCollector($this->app['db']);
        });
        $this->app->singleton(MessageCollector::class, function () {
            return new MessageCollector();
        });
        $this->app->singleton(ExceptionCollector::class, function () {
            return new ExceptionCollector();
        });
        $this->app->singleton(LarDebug::class, function ($app) {
            return new LarDebug($app->make(Server::class),$app);
        });
        $this->app->singleton('lardebug.commands.serve', function ($app) {
            return new StartDebugServer(__DIR__, config('lardebug.server.host'), config('lardebug.server.port'));
        });
        $this->commands(['lardebug.commands.serve']);
        $this->mergeConfigFrom($this->getConfigPath()."/". $this->getConfigFileName(), 'lardebug');
        $this->publishes([
            $this->getConfigPath()."/". $this->getConfigFileName() => \config_path('/lardebug.php'),
        ], 'lardebug-configs');
        $this->app->singleton(Server::class, function () {
            return new Server(config('lardebug.server.host'), config('lardebug.server.port'));
        });
    
        $this->app->singleton(ServerConfigManager::class, function ($app) {
            return new ServerConfigManager(\config('lardebug'), $this->getConfigPath());
        });

        $this->app->singleton(Console::class, function ($app) {
            return new Console($app->make(Server::class));
        });
    }
  
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
      
        $larDebug = $this->app->make(LarDebug::class);
        $larDebug->addCollector('route',$this->app->make(RouteCollector::class));
        $larDebug->addCollector('request',$this->app->make(RequestCollector::class));
        $larDebug->addCollector('query',$this->app->make(QueryCollector::class));
        $larDebug->addCollector('message',$this->app->make(MessageCollector::class));
        $larDebug->addCollector('exceptions',$this->app->make(ExceptionCollector::class));
       
        $queueEventHandler = new QueueEventHandler($this->app->make(Console::class), $this->app['events']);
        $queueEventHandler->listen();
    }
   
    private function getConfigPath()
    {
        return __DIR__ . '/../config';
    }
    private function getConfigFileName()
    {
        return '/lardebug.php';
    }
    
}
