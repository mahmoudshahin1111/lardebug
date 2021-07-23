<?php

namespace LarDebug;

use LarDebug\ServerPayload;
use Illuminate\Support\Facades\Http;

class Server
{
    private $host;
    private $port;
    private $prefix;
    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
        $this->prefix = 'lardebug';
    }
    /**
     * send payload
     *
     * @param ServerPayload $payload
     * @return void
     */
    public function sendPayload($payload)
    {
        $response = Http::post($this->getServerEndpoint() . '/' . $payload->getPrefix(), $payload->get());
        return $response;
    }
    private function getServerEndpoint()
    {
        return "http://{$this->host}:{$this->port}/{$this->prefix}";
    }
}
