<?php


namespace LarDebug\EventHandlers;

use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;

class QueueEventHandler
{
    /**
     * Undocumented variable
     *
     * @var Server
     */
    private $server;
    private $event;
    public function __construct($server, $event)
    {
        $this->server = $server;
        $this->event = $event;
    }
    public function bootstrap()
    {
        $this->event->listen(JobFailed::class, function(JobFailed $event){
            $this->handleJobFails($event);
        });
        $this->event->listen(JobProcessing::class,function(JobProcessing $event){
            $this->handleJobProcessing($event);
        });
        $this->event->listen(JobProcessed::class,function(JobProcessed $event){
            $this->handleJobProcessed($event);
        });
    }
    public function handleJobFails(JobFailed $event)
    {
        $this->server->sendPayload('console', [
            'id' => \uniqid('message_'),
            'time' => \microtime(true) * 1000,
            'body' => "[Job:{$event->job->resolveName()}] Fails",
            'meta'=>[
                'type'=>'event/queue/jobFailed',
                'jobName'=>$event->job->resolveName(),
                'exceptionMessage'=>$event->exception->getMessage(),
                'trackString'=>$event->exception->getTraceAsString()
            ]]);
    }
    public function handleJobProcessing(JobProcessing $event){
        $this->server->sendPayload('console', [
            'id' => \uniqid('message_'),
            'time' => \microtime(true) * 1000,
            'body' => "[Job:{$event->job->resolveName()}] Processing",
            'meta'=>[
                'type'=>'event/queue/JobProcessing',
                'jobName'=>$event->job->resolveName(),
            ]]);
    }
    public function handleJobProcessed(JobProcessed $event){
        $this->server->sendPayload('console', [
            'id' => \uniqid('message_'),
            'time' => \microtime(true) * 1000,
            'body' => "[Job:{$event->job->resolveName()}] Processed",
            'meta'=>[
                'type'=>'event/queue/JobProcessed',
                'jobName'=>$event->job->resolveName(),
            ]]);
    }
}
