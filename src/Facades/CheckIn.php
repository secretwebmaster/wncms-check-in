<?php

namespace Wncms\CheckIn\Facades;

use Illuminate\Support\Facades\Facade;

class CheckIn extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'check-in';
    }
}
