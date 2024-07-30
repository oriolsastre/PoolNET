<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PoolNET\service\Router;
use PoolNET\service\RouterJson;
use PoolNET\service\RouterPage;

/**
 * @coversDefaultClass \PoolNET\service\Router
 */
class RouterTest extends TestCase
{
    private ReflectionProperty $formatProp;
    private ReflectionProperty $_routersProp;
    private ReflectionProperty $_routesProp;
    /**
     * @covers ::__construct
     */
    public function testConstruct(): void
    {
        $this->getRouterProtectedProperties();

        // Amb valors per defecte
        $router = new Router();
        $this->assertInstanceOf(Router::class, $router);
        $this->assertNull($router->prefix);
        $this->assertEquals('json', $this->formatProp->getValue($router));
        $this->assertInstanceOf(stdClass::class, $this->_routersProp->getValue($router));
        $this->assertEquals(0, count(get_object_vars($this->_routersProp->getValue($router))));
        $this->assertInstanceOf(stdClass::class, $this->_routesProp->getValue($router));
        $this->assertEquals(0, count(get_object_vars($this->_routesProp->getValue($router))));

        // Amb valors predefinits
        $router2 = new Router(("/main/accio"), "html");
        $this->assertInstanceOf(Router::class, $router2);
        $this->assertEquals("/main/accio", $router2->prefix);
        $this->assertEquals('html', $this->formatProp->getValue($router2));
        $this->assertInstanceOf(stdClass::class, $this->_routersProp->getValue($router2));
        $this->assertEquals(0, count(get_object_vars($this->_routersProp->getValue($router2))));
        $this->assertInstanceOf(stdClass::class, $this->_routesProp->getValue($router2));
        $this->assertEquals(0, count(get_object_vars($this->_routesProp->getValue($router2))));
    }
    /**
     * @covers ::addRouter
     * @uses \PoolNET\service\Router
     * @uses \PoolNET\service\RouterPage
     */
    public function testAddRouter(): void
    {
        $this->getRouterProtectedProperties();
        $router1 = new Router("/", "json");
        $router2 = new RouterPage("/pages");

        // No hi ha cap router d'inici
        $this->assertInstanceOf(stdClass::class, $this->_routersProp->getValue($router1));
        $this->assertEquals(0, count(get_object_vars($this->_routersProp->getValue($router1))));

        // N'afageixo un, hi és, i és del tipus que toca.
        $router1->addRouter("/pages", $router2);
        $this->assertEquals(1, count(get_object_vars($this->_routersProp->getValue($router1))));
        $this->assertInstanceOf(Router::class, $this->_routersProp->getValue($router1)->{"/pages"});
        $this->assertEquals($router2, $this->_routersProp->getValue($router1)->{"/pages"});
    }
    /**
     * @covers ::removePrefix
     * @uses \PoolNET\service\Router
     */
    public function testRemovePrefix(): void
    {
        $prefix = "/api/accio";
        $router = new Router($prefix);
        $reflectionClass = new ReflectionClass(Router::class);
        $removePrefix = $reflectionClass->getMethod('removePrefix');
        $removePrefix->setAccessible(true);
        // Resultat esperat
        $result1 = $removePrefix->invoke($router, "/api/accio/edit/123");
        $this->assertEquals("/edit/123", $result1);
        // El prefix no hi és, no cal treure res
        $route = "/page/control";
        $result2 = $removePrefix->invoke($router, $route);
        $this->assertEquals($route, $result2);
    }
    /**
     * @covers ::removeClosingSlash
     * @uses \PoolNET\service\Router
     */
    public function testRemoveClosingSlash(): void
    {
        $router = new Router();
        $reflectionClass = new ReflectionClass(Router::class);
        $removeClosingSlash = $reflectionClass->getMethod('removeClosingSlash');
        $removeClosingSlash->setAccessible(true);
        // Resultat esperat
        $result1 = $removeClosingSlash->invoke($router, "/route/to/api/");
        $this->assertEquals("/route/to/api", $result1);
        // Manté la barra si només conté la barra
        $result2 = $removeClosingSlash->invoke($router, "/");
        $this->assertEquals("/", $result2);
        // No elimina res si no hi ha barra final
        $route = "/normal/route";
        $result3 = $removeClosingSlash->invoke($router, $route);
        $this->assertEquals($route, $result3);
    }
    /**
     * @covers ::getSuccessiveRoutes
     * @uses \PoolNET\service\Router
     */
    public function testGetSuccessiveRoutes(): void
    {
        $router = new Router();
        $reflectionClass = new ReflectionClass(Router::class);
        $getSuccessiveRoutes = $reflectionClass->getMethod('getSuccessiveRoutes');
        $getSuccessiveRoutes->setAccessible(true);
        // Resultat esperat
        $result1 = $getSuccessiveRoutes->invoke($router, "/route/to/api");
        $this->assertEquals(["/", "/route", "/route/to", "/route/to/api"], $result1);
    }
    /**
     * @covers ::use
     * @uses \PoolNET\service\Router
     * @uses \PoolNET\service\RouterJson
     * @uses ::addRouter
     * @uses ::removePrefix
     * @uses ::removeClosingSlash
     */
    public function testUse(): void
    {
        $router1 = new Router("/api");
        $router2 = new RouterJson("/control", "json");
        $router1->addRouter("/control", $router2);

        $crida = "/api/control";
        $result = $router1->use($crida, null, "GET");
    }
    /**
     * @coversNothing
     * @doesNotPerformAssertions
     */
    private function getRouterProtectedProperties(): void
    {
        $reflectionClass = new ReflectionClass(Router::class);
        $this->formatProp = $reflectionClass->getProperty('format');
        $this->formatProp->setAccessible(true);
        $this->_routersProp = $reflectionClass->getProperty('_routers');
        $this->_routersProp->setAccessible(true);
        $this->_routesProp = $reflectionClass->getProperty('_routes');
        $this->_routesProp->setAccessible(true);
    }
}
