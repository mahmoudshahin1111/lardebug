<?php

namespace LarDebug\Collectors;
class EventCollector implements ICollector{
    /**
     * array of events triggered
     *
     * @var array
     */
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