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
            $this->listenToExceptions();
  
    }
    protected function listenToExceptions(){
    
      error_reporting(-1);

      // set_error_handler([$this, 'onExceptionTriggered']);

      set_exception_handler([$this, 'onExceptionTriggered']);

      // set_exception_handler([$this, 'onExceptionTriggered']);
      // $this->handler->reportable(function (Throwable $e) {
      //   file_put_contents(\public_path("/text.txt"),"qwdqwdqwd");
      // });
      // dd($this->handler);
    }
    protected function onExceptionTriggered(Throwable $e){
      $this->sendExceptionToServer();
    }
    protected function sendExceptionToServer(){
      file_put_contents(\public_path("/text.txt"),"qwdqwdqwd");
    }
   
}