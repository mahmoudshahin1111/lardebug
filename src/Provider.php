<?php

namespace LarDebug;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Http;
use  Illuminate\Foundation\Application as App;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Lib\Collectors\MessageCollector;
use Lib\Collectors\QueryCollector;
use Lib\Collectors\RequestCollector;
use Lib\Collectors\RouteCollector;
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
        
        $collectors = [];
        $collectors = array_merge($collectors,['route'=>new \Lib\Collectors\RouteCollector($this->app->make(Router::class))]);
        $collectors = array_merge($collectors,['request'=>new \Lib\Collectors\RequestCollector($this->app->make(Request::class))]);
        $collectors = array_merge($collectors,['query'=>new \Lib\Collectors\QueryCollector($this->app->make('db'))]);
        $collectors = array_merge($collectors,['message'=>new \Lib\Collectors\MessageCollector()]);

        $server = new Server(config('lardebug.server.host'),config('lardebug.server.port'));

        $larDebug = new LarDebug($this->app,$server,$collectors);
        $this->app->singleton('larDebug',function($app)use($larDebug){
            return $larDebug;
        });
        $this->app->make(\Illuminate\Contracts\Http\Kernel::class)->pushMiddleware(\Lib\Middleware::class);
    }
    
}
