<?php

namespace PoolNET\interface\config;

use PDO;

interface Database
{
    public function connect(): PDO | null;
}
