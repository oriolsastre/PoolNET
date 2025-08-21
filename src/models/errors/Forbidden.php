<?php

namespace PoolNET\error;

use ValueError;

class Forbidden extends ValueError
{
    public function __construct()
    {
        parent::__construct('Forbidden', 403);
    }
}
