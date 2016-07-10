<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

/**
 * Class Parsedown.
 */
class EanoisParsedown extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \App\Providers\EanoisParsedown::class;
    }
}