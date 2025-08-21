<?php

namespace PoolNET\service;

use ArrayObject;
use PoolNET\interface\Middleware;

class MiddlewareArray extends ArrayObject
{
    public function add(Middleware $middleware): void
    {
        $this->append($middleware);
    }
}
