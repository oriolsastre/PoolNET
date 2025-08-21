<?php

use PoolNET\MW\LoginValidator;
use PoolNET\test\ReqResTestCase;

/**
 * @coversDefaultClass \PoolNET\middlewares\LoginValidator
 */
class LoginValidatorTest extends ReqResTestCase
{
    /**
     * @covers \PoolNET\MW\LoginValidator
     * @covers \PoolNET\MW\Validator::requiredFields
     * @uses \PoolNET\config\Request
     * @uses \PoolNET\config\Response
     */
    public function testUse()
    {
        $this->newReqRes();
        $this->req = $this->req->withBody(["usuari" => "Test", "password" => "Test123"]);

        $validator = new LoginValidator();
        $result = $validator->use($this->req, $this->res);
        $this->assertTrue($result);

        // Test falla per tipus invalid
        $this->req = $this->req->withBody(["usuari" => "Test", "password" => 123]);
        ob_start();
        $result = $validator->use($this->req, $this->res);
        $this->assertFalse($result);
        $this->assertEquals(400, http_response_code());
        $response = json_decode(ob_get_contents(), true);
        $this->assertArrayHasKey("error", $response);
        $this->assertSame("Algun camp no és del tipus correcte.", $response["error"]);
        $this->assertArrayHasKey("camps_obligatoris", $response);
        ob_end_clean();

        // Test falla per falta de camp obligatori
        $this->req = $this->req->withBody(["usuari" => "Test"]);
        ob_start();
        $result = $validator->use($this->req, $this->res);
        $this->assertFalse($result);
        $this->assertEquals(400, http_response_code());
        $response = json_decode(ob_get_contents(), true);
        $this->assertArrayHasKey("error", $response);
        $this->assertSame("Falta algun camp obligatori.", $response["error"]);
        $this->assertArrayHasKey("camps_obligatoris", $response);
        ob_end_clean();
    }
}
