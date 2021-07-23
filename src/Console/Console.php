<?php

namespace LarDebug\Console;

use LarDebug\Server;
use LarDebug\ServerPayload;
use LarDebug\Console\Message;



class Console{
    private $server;
    public function __construct(Server $server){
        $this->server = $server;
    }
    /**
     * Undocumented function
     *
     * @param Message $consoleMessage
     * @return void
     */
    public function send(Message $consoleMessage){
        $payload = new ServerPayload('console',$consoleMessage->get());
        $this->server->sendPayload($payload);
    }
}