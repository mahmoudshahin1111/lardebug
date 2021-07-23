<?php

namespace LarDebug\Facade;

use Illuminate\Support\Facades\Facade;

class LarDebug extends Facade{
    protected static function getFacadeAccessor(){
        return \LarDebug\LarDebug::class;
    }
}