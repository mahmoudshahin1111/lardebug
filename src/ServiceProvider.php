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

class ServiceProvider extends Provider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLarDebug();
        $this->registerCommands();
        $this->registerConfig();
        $this->registerServerConfigManager();
        $this->registerMiddleware();
        $this->registerServer();
        $this->registerQueueHandler();
    }
  
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make(\LarDebug::class)->bootstrap();
        $this->app->make(QueueEventHandler::class)->bootstrap();
    }
    private function registerQueueHandler()
    {
        $this->app->singleton(QueueEventHandler::class, function ($app) {
            return new QueueEventHandler($this->app->make(Server::class),$this->app['events']);
        });
    }
    private function registerServerConfigManager()
    {
        $this->app->singleton(ServerConfigManager::class, function ($app) {
            return new ServerConfigManager(\config('lardebug'), $this->getConfigPath());
        });
    }
    private function registerCommands()
    {
        $this->app->singleton('lardebug.commands.serve', function ($app) {
            return new StartDebugServer(__DIR__, config('lardebug.server.host'), config('lardebug.server.port'));
        });
        $this->commands(['lardebug.commands.serve']);
    }
    private function registerLarDebug()
    {
        $this->app->singleton(\LarDebug::class, function () {
            return new LarDebug(
                $this->getCollectors(),
            );
        });
    }
    private function registerServer()
    {
        $this->app->singleton(Server::class, function () {
            return new Server(config('lardebug.server.host'), config('lardebug.server.port'));
        });
    }
    private function registerConfig()
    {
        $this->mergeConfigFrom($this->getConfigPath()."/". $this->getConfigFileName(), 'lardebug');
        $this->publishes([
            $this->getConfigPath()."/". $this->getConfigFileName() => \config_path('/lardebug.php'),
        ], 'lardebug-configs');
    }
    private function getConfigPath()
    {
        return __DIR__ . '/../config';
    }
    private function getConfigFileName()
    {
        return '/lardebug.php';
    }
    private function getCollectors()
    {
        $collectors = [];
        $collectors = array_merge($collectors, ['route' => new RouteCollector($this->app->make(Router::class))]);
        $collectors = array_merge($collectors, ['request' => new RequestCollector($this->app->make(Request::class))]);
        $collectors = array_merge($collectors, ['query' => new QueryCollector($this->app['db'])]);
        $collectors = array_merge($collectors, ['message' => new MessageCollector()]);
        $collectors = array_merge($collectors, ['exceptions' => new ExceptionCollector()]);
        return $collectors;
    }
    private function registerMiddleware()
    {
        $this->app->make(\Illuminate\Contracts\Http\Kernel::class)->pushMiddleware(\LarDebug\Middleware::class);
    }
}
