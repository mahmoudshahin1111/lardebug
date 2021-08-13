<?php
namespace LarDebug;

use Illuminate\Support\Facades\Facade;

class LarDebugFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'LarDebug\\LarDebug';
    }
}
