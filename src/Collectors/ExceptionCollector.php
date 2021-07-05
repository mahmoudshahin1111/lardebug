<?php

namespace LarDebug\Collectors;
use LarDebug\Server;
use Throwable;
use \DebugBar\StandardDebugBar;
class ExceptionCollector{
  /**
     * array of events triggered
     *
     * @var array
     */
    private $exceptions = [];
    public function __construct(){
       
    }
    public function collect(){
        return $this->exceptions;
    }
    public function addException(\Exception $e){
       array_push($this->exceptions,[
         'message'=>$e->getMessage(),
         'code'=>$e->getCode(),
         'file'=>$e->getFile(),
         'trackString'=>$e->getTraceAsString(),
       ]);
    }
}