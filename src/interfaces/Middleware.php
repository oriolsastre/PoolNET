<?php

namespace PoolNET\interface;

use PoolNET\config\{Request, Response};

interface Middleware
{
    public function use(Request &$req, Response &$res): bool;
}
