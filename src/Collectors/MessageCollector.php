<?php

namespace LarDebug\Collectors;

class MessageCollector implements ICollector
{
    /**
     * debug messages
     *
     * @var array
     */
    protected $messages = [];
    public function __construct()
    {

    }
    /**
     * Add to collect
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function add($level,$message,$context)
    {
        array_push($this->messages,[
            'id' => \uniqid('message_'),
            'level'=>$level,
            'time' => \microtime(true) * 1000,
            'body' => $message,
        ]);
    }

    public function collect()
    {
        return $this->messages;
    }
}
