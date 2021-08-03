<?php

namespace LarDebug\Command;

use Illuminate\Console\Command;

class StartDebugServer extends Command
{
    private $dirPath;
    private $host;
    private $port;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lardebug:serve {--host=}:{--port=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start LarDebug';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($dirPath, $host, $port)
    {

        parent::__construct();
        $this->dirPath = $dirPath;
        $this->host = $host;
        $this->port = $port;
     
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->printServerStartedMessage();
        $this->startServer();
    }
    private function startServer()
    {
  
        \exec($this->getFullCommand());
    }
    private function getFullCommand(){
        return "node " . $this->getServerExecFilePath() . ' ' . $this->getCommandArgs();
    }
    private function getServerExecFilePath(){
        return $this->dirPath.'/../server/src/dir/index.js'; 
    }
    private function printServerStartedMessage()
    {
        print("server listen on " . $this->getHost().":".$this->getPort());
    }
    private function getCommandArgs()
    {
        return '--host ' . $this->getHost() . ' ' . '--port  ' . $this->getPort();
    }
    private function getHost()
    {
        return empty($this->option('host')) ?$this->host: $this->option('host');
    }
    private function getPort()
    {
        return empty($this->option('port')) ?$this->port: $this->option('port');
    }
}
