<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PoolNET\error\Forbidden;

/**
 * @covers \PoolNET\error\Forbidden
 */
class ForbiddenTest extends TestCase
{
    /**
     * @covers \PoolNET\error\Forbidden::__construct
     */
    public function testConstructor(): void
    {
        $exception = new Forbidden();
        $this->assertSame('Forbidden', $exception->getMessage());
        $this->assertSame(403, $exception->getCode());
    }
}
