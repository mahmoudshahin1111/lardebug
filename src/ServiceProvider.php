<?php

namespace LarDebug;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Application as App;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobQueued;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider as Provider;
use LarDebug\Collectors\ExceptionCollector;
use LarDebug\Collectors\MessageCollector;
use LarDebug\Collectors\QueryCollector;
use LarDebug\Collectors\RequestCollector;
use LarDebug\Collectors\RouteCollector;
use LarDebug\Command\StartDebugServer;
use LarDebug\EventHandlers\JobExceptionOccurredEventHandler;
use LarDebug\EventHandlers\JobFailedEventHandler;
use LarDebug\EventHandlers\JobProcessedEventHandler;
use LarDebug\EventHandlers\JobProcessingEventHandler;
use LarDebug\EventHandlers\JobQueuedEventHandler;
use LarDebug\EventHandlers\MessageLoggedEventHandler;
use LarDebug\EventHandlers\QueryEventHandler;
use LarDebug\Formatter\QueryFormatter;
use LarDebug\LarDebug;
use LarDebug\Middleware as LarDebugMiddleware;
use LarDebug\RequestHandler;
use LarDebug\ServerConfigManager;
use \LarDebug\Console\Console;

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
            return new QueryCollector();
        });
        $this->app->singleton(MessageCollector::class, function () {
            return new MessageCollector();
        });
        $this->app->singleton(ExceptionCollector::class, function () {
            return new ExceptionCollector();
        });
        $this->app->singleton(QueryFormatter::class, function () {
            return new QueryFormatter();
        });
        $this->app->singleton(LarDebug::class, function ($app) {
            return new LarDebug($app);
        });
        $this->app->singleton(StartDebugServer::class, function ($app) {
            return new StartDebugServer(__DIR__, config('lardebug.server.host'), config('lardebug.server.port'));
        });
        $this->app->singleton(Server::class, function () {
            return new Server(config('lardebug.server.host'), config('lardebug.server.port'));
        });

        $this->app->singleton(ServerConfigManager::class, function ($app) {
            return new ServerConfigManager(\config('lardebug'), $this->getConfigPath());
        });

        $this->app->singleton(Console::class, function ($app) {
            return new Console($app->make(Server::class));
        });
        $this->app->singleton(RequestHandler::class, function ($app) {
            return new RequestHandler($app->make(Server::class), $app->make(Handler::class));
        });
        $this->registerPublish();

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (app()->runningInConsole()) {
            // dd(1);
        }
        $larDebug = $this->app->make(LarDebug::class);

        $requestHandler = $this->app->make(RequestHandler::class);
        $requestHandler->addCollector('route', $this->app->make(RouteCollector::class));
        $requestHandler->addCollector('request', $this->app->make(RequestCollector::class));
        $requestHandler->addCollector('query', $this->app->make(QueryCollector::class));
        $requestHandler->addCollector('message', $this->app->make(MessageCollector::class));
        $requestHandler->addCollector('exceptions', $this->app->make(ExceptionCollector::class));

        $this->app['events']->listen(JobFailed::class, [JobFailedEventHandler::class, 'handle']);
        $this->app['events']->listen(JobExceptionOccurred::class, [JobExceptionOccurredEventHandler::class, 'handle']);
        $this->app['events']->listen(JobProcessing::class, [JobProcessingEventHandler::class, 'handle']);
        $this->app['events']->listen(JobProcessed::class, [JobProcessedEventHandler::class, 'handle']);
        $this->app['events']->listen(JobQueued::class, [JobQueuedEventHandler::class, 'handle']);
        $this->app['events']->listen(QueryExecuted::class, [QueryEventHandler::class, 'handle']);
        $this->app['events']->listen(MessageLogged::class, [MessageLoggedEventHandler::class, 'handle']);
    }
    private function registerPublish()
    {
        $this->commands([StartDebugServer::class]);
        $this->mergeConfigFrom($this->getConfigPath() . "/" . $this->getConfigFileName(), 'lardebug');
        $this->publishes([
            $this->getConfigPath() . "/" . $this->getConfigFileName() => \config_path('/lardebug.php'),
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
}
