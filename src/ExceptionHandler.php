<?php

namespace LarDebug;
use LarDebug\Server;
use Throwable;
class ExceptionHandler{
    /**
     * Server Instance 
     *
     * @var Server
     */
    private $server;
     /**
     * handler instance 
     *
     * @var Handler
     */
    private $handler;
    public function __construct($handler,$server){
            $this->server = $server;
            $this->handler = $handler;  
    }
    public function bootstrap(){
      $this->registerListeners();
    }
    public function registerListeners(){
      set_error_handler([$this, 'handleError']);
      set_exception_handler([$this,'handleException']);

    }
    public  function handleException($e){
      // dd(1);
      file_put_contents(\public_path("/text.txt"),"exception");
    }
    public function handleError(){
      file_put_contents(\public_path("/text.txt"),"error");
    }
    public function sendExceptionToServer(){
 
    }
   
}