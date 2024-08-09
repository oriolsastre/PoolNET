<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PoolNET\config\Request;

/**
 * @coversDefaultClass \PoolNET\config\Request
 */
class RequestTest extends TestCase
{
    public string $REQUEST_URI = "http://localhost/PoolNET/tests";
    /**
     * @covers \PoolNET\config\Request
     * @covers ::__construct
     * @covers ::getUri
     */
    public function testGetUri(): void
    {
        $request = new Request();
        $this->assertSame($this->REQUEST_URI, $request->getUri());
    }
    /**
     * @covers \PoolNET\config\Request
     * @covers ::getPath
     */
    public function testGetPath(): void
    {
        $request = new Request();
        $this->assertSame("/tests", $request->getPath());
    }
    /**
     * @covers \PoolNET\config\Request
     * @covers ::getParams
     */
    public function testGetParams(): void
    {
        $request = new Request();
        $this->assertSame('', $request->getParams());

        $originalUri = $this->REQUEST_URI;
        $_SERVER["REQUEST_URI"] = $this->REQUEST_URI . '?param1=value1&param2=value2';
        $request2 = new Request();
        $this->assertSame('param1=value1&param2=value2', $request2->getParams());
        $_SERVER["REQUEST_URI"] = $originalUri;
    }
    /**
     * @covers \PoolNET\config\Request
     * @covers ::getMethod
     */
    public function testGetMethod(): void
    {
        $request = new Request();
        $this->assertSame("get", $request->getMethod());
    }
    /**
     * @covers \PoolNET\config\Request
     * @covers ::getHeaders
     */
    public function testGetHeaders(): void
    {
        $request = new Request();
        $this->assertSame([], $request->getHeaders());

        // Simulate a request with header
        $_SERVER['HTTP_ACCEPT'] = "application/json";
        $this->assertSame(["Accept" => "application/json"], $request->getHeaders());
    }
    /**
     * @covers \PoolNET\config\Request
     * @covers ::getParsedBody
     */
    public function testGetParsedBody(): void
    {
        $request = new Request();
        $this->assertSame([], $request->getParsedBody());
        // TODO: Testejar amb un body amb dades. Potser mockejant.
    }
}
