<?php

use PoolNET\config\Env;
use PoolNET\controller\AuthLogin;
use PoolNET\test\ReqResTestCase;

/**
 * @coversDefaultClass \PoolNET\controller\AuthLogin
 */
class AuthLoginTest extends ReqResTestCase

{
    public function setUp(): void
    {
        Env::executar();
    }
    /**
     * @covers ::post
     * @uses \PoolNET\config\Database
     * @uses \PoolNET\config\Env
     * @uses \PoolNET\config\Request
     * @uses \PoolNET\config\Response
     * @uses \PoolNET\model
     * @uses \PoolNET\User
     * @uses \PoolNET\service\JwtHandler
     */
    public function testPost()
    {
        $this->newReqRes();

        // Test exit
        $this->req->body = ["usuari" => $this->testUser, "password" => $this->testPswd];
        ob_start();
        AuthLogin::post($this->req, $this->res);
        $this->assertEquals(200, http_response_code());
        $headers = $this->getAssocHeaders();
        $this->assertArrayHasKey('Set-Cookie', $headers);
        $this->assertStringContainsString('token=', $headers['Set-Cookie']);
        // TODO: token rebut

        ob_end_clean();

        $this->req->body = ["usuari" => "Test", "password" => "Test123"];
        ob_start();
        AuthLogin::post($this->req, $this->res);
        $this->assertEquals(400, http_response_code());

        ob_end_clean();
    }
}
