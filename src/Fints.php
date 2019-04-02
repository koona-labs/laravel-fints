<?php

namespace Abiturma\LaravelFints;

use Abiturma\PhpFints\BaseFints;
use Illuminate\Support\Collection;

/**
 * Class Fints
 * @package Abiturma\LaravelFints
 */
class Fints
{
    protected $fints;

    /**
     * Fints constructor.
     * @param BaseFints $fints
     */
    public function __construct(BaseFints $fints)
    {
        $this->fints = $fints;
    }

    /**
     * @param $method
     * @param $arguments
     * @return $this|Collection
     */
    public function __call($method, $arguments)
    {
        $result = $this->fints->$method(...$arguments);
        
        
        if (is_array($result)) {
            return new Collection($result);
        }
        if ($result instanceof BaseFints) {
            return $this;
        }
        return $result;
    }
}
