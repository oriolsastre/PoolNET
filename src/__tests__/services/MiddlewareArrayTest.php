<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PoolNET\MW\LoginValidator;
use PoolNET\interface\Middleware;
use PoolNET\service\MiddlewareArray;

/**
 * @coversDefaultClass \PoolNET\service\MiddlewareArray
 */
class MiddlewareArrayTest extends TestCase
{
    /**
     * @covers ::add
     */
    public function testAdd()
    {
        $mwArray = new MiddlewareArray();
        $this->assertInstanceOf(MiddlewareArray::class, $mwArray);
        $this->assertCount(0, $mwArray);

        /** @var LoginValidator $mw */
        $mw = $this->createMock(LoginValidator::class);
        $mwArray->add($mw);
        $this->assertCount(1, $mwArray);
        foreach ($mwArray as $mw) {
            $this->assertInstanceOf(Middleware::class, $mw);
        }
    }
}
