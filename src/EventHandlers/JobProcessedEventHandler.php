<?php

namespace LarDebug\EventHandlers;

use LarDebug\Console\Console;
use LarDebug\Console\Message;


class JobProcessedEventHandler{

    public function handle($event){
        $console = app(Console::class);
        $console->send(new Message("[Job:{$event->job->resolveName()}] Processed", [
            'type'=>'event/queue/JobProcessed',
            'jobName'=>$event->job->resolveName(),
        ]));
    }
}