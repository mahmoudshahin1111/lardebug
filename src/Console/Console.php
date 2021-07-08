<?php

namespace LarDebug\Console;

use \LarDebug\Server;

class Console{
    private $server;
    public function __construct(Server $server){
        $this->server = $server;
    }
    public function send(Message $consoleMessage){
        $this->server->sendPayload('console',$consoleMessage->get());
    }
}