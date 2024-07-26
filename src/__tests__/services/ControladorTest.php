<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PoolNET\config\Env;
use PoolNET\service\Controlador;

/**
 * @coversDefaultClass \PoolNET\service\Controlador
 * @uses \PoolNET\config\Database
 * @uses \PoolNET\config\Env
 */
class ControladorTest extends TestCase
{
  public function setUp(): void
  {
    Env::executar();
  }
  /**
   * @covers ::connect
   * @uses \PoolNET\config\Database
   */
  public function testConnect(): void
  {
    $controlador = new Controlador();
    $this->assertInstanceOf(Controlador::class, $controlador);
    $reflectedModel = new ReflectionClass(Controlador::class);

    $dbcnxProp = $reflectedModel->getProperty('dbcnx');
    $dbcnxProp->setAccessible(true);
    $this->assertNull($dbcnxProp->getValue());

    $connectMethod = $reflectedModel->getMethod('connect');
    $connectMethod->setAccessible(true);
    $connectMethod->invoke($controlador);
    // $dbcnx is instance of PDO after connecting
    $this->assertNotNull($dbcnxProp->getValue());
    $this->assertInstanceOf(PDO::class, $dbcnxProp->getValue());
  }
  /**
   * @covers ::respostaSimple
   * @uses \PoolNET\service\Controlador::headers
   * @uses \PoolNET\config\Env
   */
  public function testRespostaSimple(): void
  {
    // 200 OK
    Controlador::respostaSimple(200, ["Ok" => "Dades"]);
    $this->expectOutputString(json_encode(["Ok" => "Dades"]));
    $this->assertEquals(200, http_response_code());

    // 204 No Content
    ob_clean();
    Controlador::respostaSimple(204, null);
    $this->expectOutputString("");
    $this->assertEquals(204, http_response_code());

    // 500 Internal Server Error Default
    ob_clean();
    Controlador::respostaSimple();
    $this->expectOutputString(json_encode(["error" => "Alguna cosa ha fallat"]));
    $this->assertEquals(500, http_response_code());

    // 405 Method Not Allowed Default
    ob_clean();
    Controlador::respostaSimple(405);
    $this->expectOutputString(json_encode(["error" => "Mètode no permès"]));
    $this->assertEquals(405, http_response_code());
  }
}
