<?php

namespace LarDebug\EventHandlers;

use LarDebug\Console\Console;
use LarDebug\Console\Message;
use LarDebug\Collectors\MessageCollector;

class MessageLoggedEventHandler
{
    public function handle($event)
    {
        if($event->level === 'error') return;
        app(MessageCollector::class)->add($event->level,$event->message,$event->context);
    }
}
