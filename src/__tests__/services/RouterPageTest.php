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
    private ReflectionProperty $pagesProp;
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

        $rutaUsuari = "/usuari";
        $router = new RouterPage($rutaUsuari);
        $this->assertInstanceOf(RouterPage::class, $router);
        $this->assertInstanceOf(stdClass::class, $this->pagesProp->getValue($router));
        $this->assertEquals(0, count(get_object_vars($this->pagesProp->getValue($router))));

        $page = new Page("Usuari");
        $router->addPage($rutaUsuari, $page);
        $this->assertEquals(1, count(get_object_vars($this->pagesProp->getValue($router))));
        $this->assertInstanceOf(Page::class, $this->pagesProp->getValue($router)->{$rutaUsuari});
        $this->assertEquals($page, $this->pagesProp->getValue($router)->{$rutaUsuari});
    }
    /**
     * @coversNothing
     * @doesNotPerformAssertions
     */
    private function getRouterProtectedProperties(): void
    {
        $reflectionClass = new ReflectionClass(RouterPage::class);
        $this->pagesProp = $reflectionClass->getProperty('pages');
        $this->pagesProp->setAccessible(true);
    }
}
