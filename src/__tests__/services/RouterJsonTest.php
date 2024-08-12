<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
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
     */
    public function testAddController(): void
    {
        $this->getRouterProtectedProperties();

        $rutaUsuari = "/usuari";
        $router = new RouterJson($rutaUsuari);
        $this->assertInstanceOf(RouterJson::class, $router);
        $this->assertIsArray($this->controllersProp->getValue($router));
        $this->assertEquals(0, count($this->controllersProp->getValue($router)));

        // $controlador = Control::class;
        // $router->addController($rutaUsuari, $controlador);
        // $this->assertEquals(1, count(get_object_vars($this->controllersProp->getValue($router))));
        // $this->assertEquals($controlador, $this->controllersProp->getValue($router)->{$rutaUsuari});
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
