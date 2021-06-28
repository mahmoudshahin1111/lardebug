<?php

namespace LarDebug;
use LarDebug\Server;
class ExceptionHandler{
    /**
     * Server Instance 
     *
     * @var Server
     */
    private $server;
    public function __construct($server){
            $this->server = $server;
            dd($this->server);
    }
    public function sendExceptionToServer(){
        
    }
    public function onExceptionTriggered(){
      dd(1);
    }
}