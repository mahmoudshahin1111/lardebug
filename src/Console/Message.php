<?php


namespace LarDebug\Console;


class Message{
    private $id;
    private $time;
    private $body;
    private $meta;
    private $type;
    public function __construct($body,$meta=null,$type=null){
        $this->body = $body;
        $this->meta = $meta;
        $this->type = $type;
    }
    public function get(){
        return [
            'id' => \uniqid('message_'),
            'time' => \microtime(true) * 1000,
            'body' => $this->body,
            'meta'=>$this->meta,
        ];
    }
}