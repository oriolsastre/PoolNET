<?php

namespace PoolNET\service;

use ArrayObject;
use PoolNET\config\Request;
use PoolNET\config\Response;
use PoolNET\interface\Middleware;

class MiddlewareArray extends ArrayObject
{
    public function add(Middleware $middleware): void
    {
        $this->append($middleware);
    }
}
