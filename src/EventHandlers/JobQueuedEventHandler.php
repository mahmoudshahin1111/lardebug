<?php

namespace LarDebug\EventHandlers;

use LarDebug\Console\Console;
use LarDebug\Console\Message;

class JobQueuedEventHandler
{
    public function handle($event)
    {
        $console = app(Console::class);
        $jobClassName = get_class($event->job);
        $console->send(new Message("[Job:{$jobClassName}] Queued", [
            'type'=>'event/queue/JobQueued',
            'jobName'=>$jobClassName,
        ]));
    }
}
