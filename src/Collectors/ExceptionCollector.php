<?php

namespace LarDebug\Collectors;

use Error;
use Exception;
use Throwable;
use LarDebug\Server;
use \DebugBar\StandardDebugBar;
use Illuminate\Foundation\Exceptions\Handler;

class ExceptionCollector
{
    /**
       * array of events triggered
       *
       * @var array
       */
    private $exceptions = [];
    public function __construct()
    {
    }
    public function collect()
    {
        return $this->exceptions;
    }
    /**
     * Undocumented function
     *
     * @param Exception|Error $e
     * @return void
     */
    public function add($e,$renderedExceptionHtml)
    {
        array_push($this->exceptions, [
         'exceptionClass'=>get_class($e),
         'message'=>$e->getMessage(),
         'code'=>$e->getCode(),
         'file'=>$e->getFile(),
         'trackString'=>$e->getTraceAsString(),
          'exceptionHtml'=>$renderedExceptionHtml,
       ]);
    }
}
