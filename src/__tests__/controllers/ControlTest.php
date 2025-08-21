<?php

declare(strict_types=1);

use PoolNET\controller\Control;
use PoolNET\test\ReqResTestCase;

/**
 * @coversDefaultClass \PoolNET\controller\Control
 */
class ControllerControlTest extends ReqResTestCase
{
    /**
     * @covers ::get
     * @uses \PoolNET\Control
     * @uses \PoolNET\Model
     * @uses \PoolNET\config\Request
     * @uses \PoolNET\config\Response
     * 
     */
    public function testGet()
    {
        $this->newReqRes();
        $controlController = new Control();

        // Test exit
        ob_start();
        $controlController->get($this->req, $this->res);
        $this->assertEquals(200, http_response_code());

        $output = ob_get_contents();
        $outputJson = json_decode($output, true);
        $this->assertIsArray($outputJson);
        $this->assertArrayHasKey("controlId", $outputJson[0]);

        ob_end_clean();
    }
}
