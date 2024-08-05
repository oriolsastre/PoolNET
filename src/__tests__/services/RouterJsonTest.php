<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PoolNET\controller\Control;
use PoolNET\service\RouterJson;

/**
 * @coversDefaultClass \PoolNET\service\RouterJson
 */
class RouterJsonTest extends TestCase
{
    private ReflectionProperty $controllersProp;
    /**
     * @covers ::__construct
     * @covers ::addController
     * @uses \PoolNET\service\Router
     * @uses \PoolNET\service\Controlador
     * @uses \PoolNET\config\Session
     * @uses \PoolNET\service\JwtHandler
     */
    public function testAddController(): void
    {
        $this->getRouterProtectedProperties();

        $rutaUsuari = "/usuari";
        $router = new RouterJson($rutaUsuari);
        $this->assertInstanceOf(RouterJson::class, $router);
        $this->assertInstanceOf(stdClass::class, $this->controllersProp->getValue($router));
        $this->assertEquals(0, count(get_object_vars($this->controllersProp->getValue($router))));

        $controlador = Control::class;
        $router->addController($rutaUsuari, $controlador);
        $this->assertEquals(1, count(get_object_vars($this->controllersProp->getValue($router))));
        $this->assertEquals($controlador, $this->controllersProp->getValue($router)->{$rutaUsuari});
    }
    /**
     * @coversNothing
     * @doesNotPerformAssertions
     */
    private function getRouterProtectedProperties(): void
    {
        $reflectionClass = new ReflectionClass(RouterJson::class);
        $this->controllersProp = $reflectionClass->getProperty('controllers');
        $this->controllersProp->setAccessible(true);
    }
}
