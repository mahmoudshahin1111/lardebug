<?php

namespace LarDebug\EventHandlers;

use LarDebug\Console\Console;
use LarDebug\Console\Message;


class JobExceptionOccurredEventHandler{

    public function handle($event){
        $console = app(Console::class);
        $console->send(new Message("[Job:{$event->job->resolveName()}] Exception Occurred", [
            'type'=>'event/queue/jobExceptionOccurred',
            'jobName'=>$event->job->resolveName(),
            'exceptionMessage'=>$event->exception->getMessage(),
            'trackString'=>$event->exception->getTraceAsString()
        ]));
    }
}