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
     * @uses \PoolNET\config\database\Database
     * @uses \PoolNET\config\Env
     * @uses \PoolNET\config\Request
     * @uses \PoolNET\config\Response
     * @uses \PoolNET\model
     * @uses \PoolNET\User
     * @uses \PoolNET\service\JwtHandler
     */
    public function testCrud()
    {
        $this->newReqRes();
        $authLoginController = new AuthLogin();

        // Test exit
        $this->req = $this->req->withBody(["usuari" => $this->testUser, "password" => $this->testPswd]);
        ob_start();
        $authLoginController->post($this->req, $this->res);
        $this->assertEquals(302, http_response_code());
        $headers = $this->getAssocHeaders();
        $this->assertArrayHasKey('Set-Cookie', $headers);
        $this->assertStringContainsString('token=', $headers['Set-Cookie']);
        ob_end_clean();

        // Test error
        $this->newReqRes();
        $this->req = $this->req->withBody(["usuari" => "Test", "password" => "Test123"]);
        ob_start();
        $authLoginController->post($this->req, $this->res);
        $this->assertEquals(400, http_response_code());

        ob_end_clean();
    }
}
