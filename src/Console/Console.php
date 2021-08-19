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
        if ($this->isLarDebugDisabled()) {
            return;
        }
        $payload = new ServerPayload('console',$consoleMessage->get());
        $this->server->sendPayload($payload);
    }
    private function isLarDebugDisabled(){
        return !\config('app.debug') || !\config('lardebug.enabled');
    }
}