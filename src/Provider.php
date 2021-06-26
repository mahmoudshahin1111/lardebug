<?php

namespace LarDebug;

use Illuminate\Foundation\Application as App;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use LarDebug\Collectors\MessageCollector;
use LarDebug\Collectors\QueryCollector;
use LarDebug\Collectors\RequestCollector;
use LarDebug\Collectors\RouteCollector;
use LarDebug\Command\StartDebugServer;

class Provider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMiddleware();
        $this->registerCommands();
        $this->registerPublishConfigCommand();

        $host = config('lardebug.server.host');
        $port = config('lardebug.server.port');

        $server = new Server($host, $port);
        $larDebug = new LarDebug($this->app, $server, $this->getCollectors());
        $this->app->singleton('lardebug', function ($app) use ($larDebug) {
            return $larDebug;
        });
        $this->app->singleton('lardebug.commands.serve', function ($app) use ($larDebug, $host, $port) {
            return new StartDebugServer(__DIR__, $host, $port);
        });
   
    }
    private function registerPublishConfigCommand(){
        // dd(__DIR__.'/config/lardebug.php');
        $this->publishes([
            __DIR__.'/config/lardebug.php'=>\config_path('/lardebug.php')
        ],'lardebug-configs');
    }
    private function getCollectors()
    {
        $collectors = [];
        $collectors = array_merge($collectors, ['route' => new RouteCollector($this->app->make(Router::class))]);
        $collectors = array_merge($collectors, ['request' => new RequestCollector($this->app->make(Request::class))]);
        $collectors = array_merge($collectors, ['query' => new QueryCollector($this->app->make('db'))]);
        $collectors = array_merge($collectors, ['message' => new MessageCollector()]);
        return $collectors;
    }
    private function registerMiddleware()
    {
        $this->app->make(\Illuminate\Contracts\Http\Kernel::class)->pushMiddleware(\LarDebug\Middleware::class);
    }
    private function registerCommands()
    {

        $this->commands(['lardebug.commands.serve']);
    }

}
