<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PoolNET\service\Page;

/**
 * @coversDefaultClass \PoolNET\service\Page
 */
class PageTest extends TestCase
{
    /**
     * @covers ::__construct
     * @uses \PoolNET\config\Session
     * @uses \PoolNET\service\JwtHandler
     */
    public function testConstruct(): void
    {
        $page = new Page("Test");
        $this->assertInstanceOf(Page::class, $page);
    }
}
