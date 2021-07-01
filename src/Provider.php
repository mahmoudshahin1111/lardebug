<?php

namespace LarDebug;

use Illuminate\Foundation\Application as App;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use LarDebug\Collectors\EventCollector;
use LarDebug\Collectors\MessageCollector;
use LarDebug\Collectors\QueryCollector;
use LarDebug\Collectors\RequestCollector;
use LarDebug\Collectors\RouteCollector;
use LarDebug\Command\StartDebugServer;
use LarDebug\ServerConfigManager;
use Illuminate\Foundation\Bootstrap\HandleExceptions;

class Provider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
        $this->registerConfig();
        $this->registerServerConfigManager();
        $this->registerMiddleware();
        $this->registerServer();
        $this->registerExceptionHandler();
        $this->registerLarDebug();
        $this->registerCommands();
    }
   
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make(ServerConfigManager::class)->boot();
    }
    private function registerServerConfigManager(){
        $this->app->singleton(ServerConfigManager::class, function ($app) {
            return new ServerConfigManager(\config('lardebug'),$this->getConfigPath());
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
        $this->app->singleton('lardebug', function () {
            return new LarDebug(
                $this->app->make('lardebug.server'),
                $this->getCollectors(),
            );
        });
    }
    private function registerExceptionHandler()
    {
        $this->app->singleton('lardebug.exceptionHandler', function () {
            return new ExceptionHandler(app(HandleExceptions::class), $this->app->make('lardebug.server'));
        });
    }
    private function registerServer()
    {
        $this->app->singleton('lardebug.server', function () {
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
    private function getConfigFileName(){
        return '/lardebug.php';
    }
    private function getCollectors()
    {
        $collectors = [];
        $collectors = array_merge($collectors, ['route' => new RouteCollector($this->app->make(Router::class))]);
        $collectors = array_merge($collectors, ['request' => new RequestCollector($this->app->make(Request::class))]);
        $collectors = array_merge($collectors, ['query' => new QueryCollector($this->app['db'])]);
        $collectors = array_merge($collectors, ['message' => new MessageCollector()]);
        $collectors = array_merge($collectors, ['events' => new EventCollector($this->app['events'])]);
        return $collectors;
    }
    private function registerMiddleware()
    {
        $this->app->make(\Illuminate\Contracts\Http\Kernel::class)->pushMiddleware(\LarDebug\Middleware::class);
    }
}
