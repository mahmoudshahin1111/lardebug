<?php


namespace LarDebug\Console;


class Message{
    private $id;
    private $time;
    private $body;
    private $meta;
    private $prefix;
    public function __construct($body,$meta=null,$prefix=null){
        $this->body = $body;
        $this->meta = $meta;
        $this->prefix = isset($prefix)?'message':'';
    }
    public function get(){
        return [
            'id' => \uniqid($this->prefix."_"),
            'time' => \microtime(true) * 1000,
            'body' => $this->body,
            'meta'=>$this->meta,
        ];
    }
}