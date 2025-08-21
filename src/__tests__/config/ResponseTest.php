<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PoolNET\config\Response;

/**
 * @coversDefaultClass \PoolNET\config\Response
 */
class ResponseTest extends TestCase
{
    private ReflectionProperty $headers;
    private ReflectionProperty $status;
    private ReflectionMethod $sendHeaders;
    private ReflectionMethod $defaultResponse;
    private array $header1 = ["key" => "Content-Type", "value" => 'application/json'];
    private array $header2 = ["key" => "Accept", "value" => ['application/json', 'text/html']];

    public function setUp(): void
    {
        $this->getResponsePrivates();
    }
    /**
     * @covers ::withHeader
     */
    public function testWithHeader(): void
    {
        $response = new Response();
        // Check initial values
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame([], $this->headers->getValue($response));
        $this->assertEquals(200, $this->status->getValue($response));

        // Using string
        $response->withHeader($this->header1["key"], $this->header1["value"]);
        $this->assertSame($this->header1["value"], $this->headers->getValue($response)[$this->header1["key"]]);
        // Using array as header value
        $response->withHeader($this->header2["key"], $this->header2["value"]);
        $this->assertSame(implode(",", $this->header2["value"]), $this->headers->getValue($response)[$this->header2["key"]]);
    }
    /**
     * @covers ::sendHeaders
     * @uses \PoolNET\config\Response
     */
    public function testSendHeaders(): void
    {
        ob_start();
        $response = new Response();
        $response->withHeader($this->header1["key"], $this->header1["value"]);
        $this->sendHeaders->invoke($response);

        $this->assertTrue(in_array('Content-Type: application/json', xdebug_get_headers()));
        ob_end_clean();
    }
    /**
     * @covers ::withStatus
     * @uses \PoolNET\config\Response
     */
    public function testWithStatus(): void
    {
        $response = new Response();
        // Default is 200
        $this->assertEquals(200, $this->status->getValue($response));
        // Custom status
        $result = $response->withStatus(404);
        $this->assertEquals(404, $this->status->getValue($response));
        $this->assertSame($response, $result);
    }
    /**
     * @covers ::toJson
     * @covers ::defaultResponse
     * @uses \PoolNET\config\Response
     */
    public function testToJson(): void
    {
        $body = ["data" => "value"];
        $response = new Response();

        ob_start();
        // Normal case
        $response->toJson($body);
        $this->assertTrue(in_array('Content-Type: application/json', xdebug_get_headers()));
        $this->assertEquals(200, http_response_code());
        $this->assertSame(json_encode($body), ob_get_contents());
        ob_end_clean();

        // Default response 405
        ob_start();
        $response->withStatus(405)->toJson(null);
        $this->assertTrue(in_array('Content-Type: application/json', xdebug_get_headers()));
        $this->assertEquals(405, http_response_code());
        $this->assertSame(json_encode(["error" => "Mètode no permès"]), ob_get_contents());
        ob_end_clean();

        // Default response 500
        ob_start();
        $response->withStatus(500)->toJson(null);
        $this->assertTrue(in_array('Content-Type: application/json', xdebug_get_headers()));
        $this->assertEquals(500, http_response_code());
        $this->assertSame(json_encode(["error" => "Alguna cosa ha fallat"]), ob_get_contents());
        ob_end_clean();
    }
    /**
     * @covers ::handleError
     * @uses \PoolNET\config\Response
     */
    public function testHandleError(): void
    {
        $response = new Response();

        // Database error
        ob_start();
        $response->handleError(new PDOException());
        $this->assertTrue(in_array('Content-Type: application/json', xdebug_get_headers()));
        $this->assertEquals(500, http_response_code());
        $this->assertSame(json_encode(["error" => "Error amb la base de dades"]), ob_get_contents());
        ob_end_clean();

        // Generic error
        ob_start();
        $response->handleError(new Exception());
        $this->assertTrue(in_array('Content-Type: application/json', xdebug_get_headers()));
        $this->assertEquals(500, http_response_code());
        $this->assertSame(json_encode(["error" => "Alguna cosa ha fallat"]), ob_get_contents());
        ob_end_clean();
    }
    /**
     * @coversNothing
     * @doesNotPerformAssertions
     */
    private function getResponsePrivates(): void
    {
        $reflectionClass = new ReflectionClass(Response::class);
        $this->headers = $reflectionClass->getProperty('headers');
        $this->headers->setAccessible(true);
        $this->status = $reflectionClass->getProperty('status');
        $this->status->setAccessible(true);
        $this->sendHeaders = $reflectionClass->getMethod('sendHeaders');
        $this->sendHeaders->setAccessible(true);
        $this->defaultResponse = $reflectionClass->getMethod('defaultResponse');
        $this->defaultResponse->setAccessible(true);
    }
}
