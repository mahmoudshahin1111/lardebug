<?php




namespace LarDebug\Collectors;

use  Illuminate\Foundation\Application as App;
use Illuminate\Routing\Router;
use Illuminate\Routing\Route;

class RouteCollector{
    protected $router;
    protected $payload;
    public function __construct(Router $router){
        $this->router = $router;
    }
    public function collect(){
        return $this->collectCurrentRoute();
    }
    protected function collectCurrentRoute(){
        return $this->getRouteInfo($this->router->getCurrentRoute());
    }
    protected function collectRoutes(){
        $routes = [];
        foreach($this->router->getRoutes() as $route){
            array_push($routes,$this->getRouteInfo($route));
        }
        return $routes;
    }
    protected function getRouteInfo($route){
        return [
            'prefix'=>$route->getPrefix(),
            'uri'=>$route->uri(),
            'name'=>$route->getName(),
            'method'=>$route->methods(),
            'controller'=>$route->getActionName(),
            'controllerMethod'=>$route->getActionMethod(),
            'middleware'=>$route->gatherMiddleware(),
        ];
    }
}