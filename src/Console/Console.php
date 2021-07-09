<?php

namespace LarDebug\Console;

use LarDebug\Server;
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
        $this->server->sendPayload('console',$consoleMessage->get());
    }
}