<?php

namespace PoolNET\test;

use PHPUnit\Framework\TestCase;
use PoolNET\config\{Request, Response};

abstract class ReqResTestCase extends TestCase
{
    protected Request $req;
    protected Response $res;
    protected string $testUser = "Test";
    protected string $testPswd = "Password123!";

    protected function newReqRes(): void
    {
        $this->req = new Request();
        $this->res = new Response();
    }
    protected function getAssocHeaders(): array
    {
        $headers = xdebug_get_headers();
        $assocHeaders = array();
        foreach ($headers as $header) {
            $header = explode(':', $header);
            $assocHeaders[$header[0]] = $header[1];
        }
        return $assocHeaders;
    }
}
