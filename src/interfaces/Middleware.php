<?php

namespace PoolNET\interface;

use PoolNET\interface\config\{Request, Response};

interface Middleware
{
    public function use(Request &$req, Response &$res): bool;
}
