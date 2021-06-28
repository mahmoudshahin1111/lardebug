<?php

namespace LarDebug\Collectors;

class MessageCollector implements ICollector
{
    protected $messages = [];
    public function __construct()
    {

    }
    public function addMessage($body)
    {
        array_push($this->messages, $this->buildMessageBody($body));
    }
    public function buildMessageBody($body)
    {
        return [
            'id' => \uniqid('message_'),
            'time' => \microtime(true) * 1000,
            'body' => $body,
        ];
    }

    public function collect()
    {
        return $this->messages;
    }
}
