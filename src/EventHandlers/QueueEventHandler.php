<?php


namespace LarDebug\EventHandlers;

use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobExceptionOccurred;
use LarDebug\Console\Console;
use LarDebug\Console\Message as ConsoleMessage;

class QueueEventHandler
{
    /**
     * Undocumented variable
     *
     * @var Console
     */
    private $console;
    private $event;
    public function __construct($console, $event)
    {
        $this->console = $console;
        $this->event = $event;
    }
    public function listen()
    {
        $this->event->listen(JobFailed::class, function (JobFailed $event) {
            $this->handleJobFails($event);
        });
        $this->event->listen(JobExceptionOccurred::class, function (JobExceptionOccurred $event) {
            $this->handleJobException($event);
        });
        $this->event->listen(JobProcessing::class, function (JobProcessing $event) {
            $this->handleJobProcessing($event);
        });
        $this->event->listen(JobProcessed::class, function (JobProcessed $event) {
            $this->handleJobProcessed($event);
        });
    }
    public function handleJobException(JobFailed $event)
    {
        $this->console->send(new ConsoleMessage("[Job:{$event->job->resolveName()}] Fails", [
            'type'=>'event/queue/jobException',
            'jobName'=>$event->job->resolveName(),
            'exceptionMessage'=>$event->exception->getMessage(),
            'trackString'=>$event->exception->getTraceAsString()
        ]));
    }
    public function handleJobFails(JobFailed $event)
    {
        $this->console->send(new ConsoleMessage("[Job:{$event->job->resolveName()}] Fails", [
            'type'=>'event/queue/jobFailed',
            'jobName'=>$event->job->resolveName(),
            'exceptionMessage'=>$event->exception->getMessage(),
            'trackString'=>$event->exception->getTraceAsString()
        ]));
    }
    public function handleJobProcessing(JobProcessing $event)
    {
        $this->console->send(new ConsoleMessage("[Job:{$event->job->resolveName()}] Processing", [
            'type'=>'event/queue/JobProcessing',
            'jobName'=>$event->job->resolveName(),
        ]));
    }
    public function handleJobProcessed(JobProcessed $event)
    {
        $this->console->send(new ConsoleMessage("[Job:{$event->job->resolveName()}] Processed", [
            'type'=>'event/queue/JobProcessed',
            'jobName'=>$event->job->resolveName(),
        ]));
    }
}
