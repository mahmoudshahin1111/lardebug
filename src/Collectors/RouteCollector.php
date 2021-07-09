<?php




namespace LarDebug\Collectors;

use  Illuminate\Foundation\Application as App;
use Illuminate\Routing\Router;
use Illuminate\Routing\Route;

class RouteCollector implements ICollector{
    protected $router;
    protected $payload;
    public function __construct(Router $router){
        $this->router = $router;
    }
    public function collect(){
        return $this->collectCurrentRoute();
    }
    protected function collectCurrentRoute(){
        if($this->router->getCurrentRoute()){
            return $this->getRouteInfo($this->router->getCurrentRoute());
        }
        return null;
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
    protected function collectRoutes(){
        $routes = [];
        foreach($this->router->getRoutes() as $route){
            array_push($routes,$this->getRouteInfo($route));
        }
        return $routes;
    }
}