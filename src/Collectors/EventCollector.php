<?php

namespace LarDebug\Collectors;
class EventCollector implements ICollector{
    private $triggeredEvents = [];
    public function __construct($event){
        $event->listen('*',[$this,'onEventTriggered']);
    }
    public function collect(){
        return $this->triggeredEvents;
    }
    public function onEventTriggered($event){
       array_push($this->triggeredEvents,$event);
    }
}