<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PoolNET\config\Env;
use PoolNET\service\JwtHandler;

/**
 * @covers \PoolNET\service\JwtHandler
 */
class JwtHandlerTest extends TestCase
{
  public function setUp(): void
  {
    Env::executar();
  }
  /**
   * @coversNothing
   * @doesNotPerformAssertions
   */
  private function getObjectProtectedProperty(string $property)
  {
    $reflectionClass = new ReflectionClass('PoolNET\service\JwtHandler');
    $reflectionProperty = $reflectionClass->getProperty($property);
    $reflectionProperty->setAccessible(true);
    return $reflectionProperty->getValue((object) $reflectionClass->newInstance());
  }
  /**
   * @covers \PoolNET\service\JwtHandler::__construct
   * @uses \PoolNET\config\Env
   */
  public function testConstructor(): void
  {
    date_default_timezone_set('Europe/Berlin');
    $this->assertSame('Europe/Berlin', date_default_timezone_get());
    // Test that the constructor sets the correct issuedAt value
    $this->assertSame(time(), $this->getObjectProtectedProperty('issuedAt'));
    // Test that the constructor sets the correct expire value
    $this->assertSame(time() + 3600, $this->getObjectProtectedProperty('expire'));
    // Test that the constructor sets the correct jwtSecret value
    $this->assertSame((string) getenv('ENV_JWTSecret'), $this->getObjectProtectedProperty('jwtSecret'));
  }
  /**
   * @covers \PoolNET\service\JwtHandler::jwtEncodeData
   * @uses \PoolNET\config\Env
   */
  public function testJwtEncodeData(): void
  {
    $jwtHandler = new JwtHandler();
    $data = ['foo' => 'bar'];
    $token = $jwtHandler->jwtEncodeData('poolnet', $data);
    $this->assertIsString($token);
  }

  /**
   * @covers \PoolNET\service\JwtHandler::jwtDecodeData
   * @uses \PoolNET\config\Env
   * @uses \PoolNET\error\InvalidJwtToken
   */
  public function testJwtDecodeData(): void
  {
    // Testejant l'èxit
    $jwtHandler = new JwtHandler();
    $token = $jwtHandler->jwtEncodeData('poolnet', ['foo' => 'bar']);
    $decoded = $jwtHandler->jwtDecodeData($token);
    $this->assertSame('bar', $decoded->foo);

    // Testejant l'error
    $this->expectException('\PoolNET\error\InvalidJwtToken');
    $this->expectExceptionMessage('Invalid JWT token');
    $invalidToken = "thisIsARandomStringWhichIsNotAValidJWT";
    $jwtHandler->jwtDecodeData($invalidToken);
  }
}
