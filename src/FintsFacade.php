<?php

namespace Abiturma\LaravelFints;

use Illuminate\Support\Facades\Facade;

/**
 * Class FintsFacade
 * @package Abiturma\LaravelFints
 */
class FintsFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Fints';
    }
}
