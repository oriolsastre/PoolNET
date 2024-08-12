<?php

namespace PoolNET\test;

use PHPUnit\Framework\TestCase;
use PoolNET\config\{Request, Response};

abstract class ReqResTestCase extends TestCase
{
    protected Request $req;
    protected Response $res;

    protected function newReqRes(): void
    {
        $this->req = new Request();
        $this->res = new Response();
    }
}
