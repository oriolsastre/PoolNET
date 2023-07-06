<?php

namespace PoolNET\config;

use ValueError;

class InvalidUniqueKey extends ValueError
{
  public function __construct()
  {
    parent::__construct('Invalid unique key', 400);
  }
}
