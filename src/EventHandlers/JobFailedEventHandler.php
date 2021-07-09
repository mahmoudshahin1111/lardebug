<?php

namespace LarDebug\EventHandlers;

use LarDebug\Console\Console;
use LarDebug\Console\Message;

class JobFailedEventHandler
{
    public function handle($event)
    {
        $console = app(Console::class);
        $console->send(new Message("[Job:{$event->job->resolveName()}] Failed", [
            'type'=>'event/queue/jobException',
            'jobName'=>$event->job->resolveName(),
            'exceptionMessage'=>$event->exception->getMessage(),
            'trackString'=>$event->exception->getTraceAsString()
        ]));
    }
}
