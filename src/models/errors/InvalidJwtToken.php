<?php

namespace PoolNET\error;

use ValueError;

class InvalidJwtToken extends ValueError
{
  public function __construct()
  {
    parent::__construct('Invalid JWT token', 400);
  }
}
