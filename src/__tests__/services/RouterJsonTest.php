<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PoolNET\service\Controlador;
use PoolNET\service\Page;
use PoolNET\service\RouterJson;

/**
 * @coversDefaultClass \PoolNET\service\RouterJson
 */
class RouterJsonTest extends TestCase
{
    private ReflectionProperty $_controllersProp;
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

        $router = new RouterJson("/usuari");
        $this->assertInstanceOf(RouterJson::class, $router);
        $this->assertInstanceOf(stdClass::class, $this->_controllersProp->getValue($router));
        $this->assertEquals(0, count(get_object_vars($this->_controllersProp->getValue($router))));

        $controlador = new Controlador();
        $router->addController("/usuari", $controlador);
        $this->assertEquals(1, count(get_object_vars($this->_controllersProp->getValue($router))));
        $this->assertInstanceOf(Controlador::class, $this->_controllersProp->getValue($router)->{"/usuari"});
        $this->assertEquals($controlador, $this->_controllersProp->getValue($router)->{"/usuari"});
    }
    /**
     * @coversNothing
     * @doesNotPerformAssertions
     */
    private function getRouterProtectedProperties(): void
    {
        $reflectionClass = new ReflectionClass(RouterJson::class);
        $this->_controllersProp = $reflectionClass->getProperty('_controllers');
        $this->_controllersProp->setAccessible(true);
    }
}
