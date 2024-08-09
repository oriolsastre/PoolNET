<?php

namespace PoolNET\interface;

use PoolNET\config\Request;

interface Middleware
{
    public static function use(Request $req): void;
}
