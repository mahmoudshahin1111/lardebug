<?php


namespace LarDebug;
use Illuminate\Support\Facades\Http;
use  Illuminate\Foundation\Application as App;

class Server{
    private $host;
    private $port;
    private $prefix;
    public function __construct($host,$port){
        $this->host = $host;
        $this->port = $port;
        $this->prefix = 'lardebug';
    }
    public function sendStartSignal()
    {
        $this->sendPayload('start', []);
    }
    public function sendPayload($prefix,$payload){
        $response = Http::post($this->getServerEndpoint().'/'.$prefix,$payload);
        return $response;
    } 
    public function sendEndSignal()
    {
        $this->sendPayload('end', []);
    }
    private function getServerEndpoint(){
        return "http://{$this->host}:{$this->port}/{$this->prefix}";
    }
}