<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PoolNET\service\Page;
use PoolNET\service\RouterPage;

/**
 * @coversDefaultClass \PoolNET\service\RouterPage
 */
class RouterPageTest extends TestCase
{
    private ReflectionProperty $_pagesProp;
    /**
     * @covers ::__construct
     * @covers ::addPage
     * @uses \PoolNET\service\Router
     * @uses \PoolNET\service\Page
     * @uses \PoolNET\config\Session
     * @uses \PoolNET\service\JwtHandler
     */
    public function testAddPage(): void
    {
        $this->getRouterProtectedProperties();

        $router = new RouterPage("/usuari");
        $this->assertInstanceOf(RouterPage::class, $router);
        $this->assertInstanceOf(stdClass::class, $this->_pagesProp->getValue($router));
        $this->assertEquals(0, count(get_object_vars($this->_pagesProp->getValue($router))));

        $page = new Page("Usuari");
        $router->addPage("/usuari", $page);
        $this->assertEquals(1, count(get_object_vars($this->_pagesProp->getValue($router))));
        $this->assertInstanceOf(Page::class, $this->_pagesProp->getValue($router)->{"/usuari"});
        $this->assertEquals($page, $this->_pagesProp->getValue($router)->{"/usuari"});
    }
    /**
     * @coversNothing
     * @doesNotPerformAssertions
     */
    private function getRouterProtectedProperties(): void
    {
        $reflectionClass = new ReflectionClass(RouterPage::class);
        $this->_pagesProp = $reflectionClass->getProperty('_pages');
        $this->_pagesProp->setAccessible(true);
    }
}
