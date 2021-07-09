<?php

namespace LarDebug\EventHandlers;

use LarDebug\Console\Console;
use LarDebug\Console\Message;


class JobProcessingEventHandler{

    public function handle($event){
        $console = app(Console::class);
        $console->send(new Message("[Job:{$event->job->resolveName()}] Processing", [
            'type'=>'event/queue/JobProcessing',
            'jobName'=>$event->job->resolveName(),
        ]));
    }
}