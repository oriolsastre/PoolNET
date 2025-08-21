<?php

declare(strict_types=1);

use PoolNET\interface\Controller\CRUD;
use PoolNET\interface\config\Request;
use PoolNET\interface\config\Response;
use PoolNET\service\RouterJson;
use PoolNET\test\ReqResTestCase;

/**
 * @coversDefaultClass \PoolNET\service\RouterJson
 */
class RouterJsonTest extends ReqResTestCase
{
    private ReflectionProperty $controllersProp;

    /**
     * @covers ::__construct
     * @covers ::get
     * @covers ::post
     * @covers ::patch
     * @covers ::delete
     * @covers ::addController
     * @uses \PoolNET\service\Router
     * @uses \PoolNET\service\RouterJson
     */
    public function testCrud(): void
    {
        $this->getRouterProtectedProperties();

        $rutaTest = "/test";
        $router = new RouterJson($rutaTest);
        $this->assertInstanceOf(RouterJson::class, $router);
        $this->assertIsArray($this->controllersProp->getValue($router));
        $this->assertEquals(0, count($this->controllersProp->getValue($router)));

        $crudMock = $this->getCrudMock();

        $router->get("/", $crudMock);
        $this->assertEquals(1, count($this->controllersProp->getValue($router)["/"]));

        $router->post("/", $crudMock);
        $this->assertEquals(2, count($this->controllersProp->getValue($router)["/"]));

        $router->patch("/", $crudMock);
        $this->assertEquals(3, count($this->controllersProp->getValue($router)["/"]));

        $router->delete("/", $crudMock);
        $this->assertEquals(4, count($this->controllersProp->getValue($router)["/"]));

        $rutaProp = $this->controllersProp->getValue($router)["/"];

        $this->assertEquals($crudMock, $rutaProp["get"]["controller"]);
        $this->assertEquals($crudMock, $rutaProp["post"]["controller"]);
        $this->assertEquals($crudMock, $rutaProp["patch"]["controller"]);
        $this->assertEquals($crudMock, $rutaProp["delete"]["controller"]);
    }
    /**
     * @covers ::use
     * @covers ::useController
     * @covers ::useMethod
     * @uses \PoolNET\service\Router
     * @uses \PoolNET\service\RouterJson
     * @uses \PoolNET\config\Request
     * @uses \PoolNET\config\Response
     * */
    public function testUse(): void
    {
        $router = new RouterJson("/test");

        $crudMock = $this->getCrudMock();
        $router->get("", $crudMock);

        // Test exit. Es crida el controlador afegit en aquesta ruta amb aquest mètode. El retorn és false ja que no ha de seguir buscant ruta/controlador
        $this->newReqRes();
        $req = $this->req->withPath("/test");
        $res = $this->res;
        $this->assertFalse($router->use($req, $res));

        // Test error. Ruta trobada, mètode incorrecte.
        $this->newReqRes();
        $req = $this->req->withPath("/test")->withMethod("post");
        $res = $this->res;
        ob_start();
        $this->assertFalse($router->use($req, $res));
        $output = json_decode(ob_get_contents(), true);
        $this->assertArrayHasKey("error", $output);
        $this->assertEquals("Mètode no permès", $output["error"]);
        $this->assertEquals(405, http_response_code());
        ob_end_clean();

        // Test error. Ruta no trobada.
        $this->newReqRes();
        $req = $this->req->withPath("/testError");
        $res = $this->res;
        ob_start();
        $this->assertTrue($router->use($req, $res));
        $output = json_decode(ob_get_contents(), true);
        $this->assertArrayHasKey("error", $output);
        $this->assertEquals("Ruta no trobada", $output["error"]);
        $this->assertEquals(404, http_response_code());
        ob_end_clean();
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
    private function getCrudMock(): CRUD
    {
        $crudMock = $this->createMock(CRUD::class);
        $crudMock->method('get')->with($this->isInstanceOf(Request::class), $this->isInstanceOf(Response::class));
        $crudMock->method('post')->with($this->isInstanceOf(Request::class), $this->isInstanceOf(Response::class));
        $crudMock->method('patch')->with($this->isInstanceOf(Request::class), $this->isInstanceOf(Response::class));
        $crudMock->method('delete')->with($this->isInstanceOf(Request::class), $this->isInstanceOf(Response::class));
        /** @var CRUD $crudMock */
        return $crudMock;
    }
}
