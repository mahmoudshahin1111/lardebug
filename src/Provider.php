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
use LarDebug\Collectors\EventCollector;
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
        $this->registerCommands();
        $this->registerConfig();
        $this->registerMiddleware();

        $this->app->singleton('lardebug.server', function () {
            return new Server(config('lardebug.server.host'), config('lardebug.server.port'));
        });
        $larDebug = new LarDebug($this->app, $this->app->make('lardebug.server'), $this->getCollectors());
        $this->app->singleton('lardebug', function ($app) use ($larDebug) {
            return $larDebug;
        });
        $this->app->singleton('lardebug.commands.serve', function ($app) use ($larDebug) {
            return new StartDebugServer(__DIR__, config('lardebug.server.host'), config('lardebug.server.port'));
        });
        // dd($this->app['exception']);
    }
    private function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/lardebug.php', 'lardebug');
        $this->publishes([
            __DIR__ . '/config/lardebug.php' => \config_path('/lardebug.php'),
        ], 'lardebug-configs');
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
    private function registerCommands()
    {

        $this->commands(['lardebug.commands.serve']);
    }

}
