<?php

namespace LarDebug\EventHandlers;

use LarDebug\Console\Console;
use LarDebug\Console\Message;
use LarDebug\Collectors\QueryCollector;


class QueryEventHandler{

    public function handle($event){
       // if class executed not from console add event to collectors otherwise send to console
       app(QueryCollector::class)->addQuery($event->sql,$event->bindings,$event->time);
    }
}