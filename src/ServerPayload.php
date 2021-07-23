<?php



class ServerPayload{
    private $id;
    private $time;
    private $prefix;
    private $body;
    public function __construct($prefix,$body){
        $this->prefix = $prefix;
        $this->body = $body;
        $this->generateId();
        $this->generateTime();
    }   

    private function generateTime(){
        $this->id =  uniqid($this->prefix."_");
    }
    private function generateId(){
        $this->time = \microtime(true) * 1000;
    }
    public function setPrefix($prefix){
        $this->body = $prefix;
    }
    public function getPrefix(){
        return $this->prefix;
    }
    public function getBody(){
        return $this->body;
    }
    public function setBody($body){
        $this->body = $body;
    }
}