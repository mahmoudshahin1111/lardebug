<?php

namespace LarDebug;

class ServerConfigManager
{
    private $configPath;
    private $configs;
    /**
     * file config
     *
     * @param array $configs
     */
    public function __construct($configs,$configPath)
    {
        $this->configs = $configs;
        $this->configPath = $configPath;
    }
    public function run()
    {
        $this->updateServerConfigFile();
    }
    public function updateServerConfigFile()
    {
        $dataAsJson = $this->parseToJson($this->configs);
        $this->saveConfigAsJson($dataAsJson);
    }
    private function saveConfigAsJson($data)
    {
        file_put_contents($this->configPath . "/lardebug.json", $data);
    }
    private function parseToJson($configData)
    {
        return \json_encode($configData);
    }
}
