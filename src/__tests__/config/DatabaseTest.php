<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PoolNET\config\Env;
use PoolNET\config\database\Database;

/**
 * @covers \PoolNET\config\database\Database
 */
class DatabaseTest extends TestCase
{
  public function setUp(): void
  {
    Env::executar();
  }
  /**
   * @covers \PoolNET\config\database\Database::__construct
   * @uses \PoolNET\config\Env
   */
  public function testConstructor(): void
  {
    Env::executar();
    $database = new Database();
    $this->assertInstanceOf(Database::class, $database);
    $reflectedDB = new ReflectionObject($database);
    $reflectedDB->getProperty('dbName')->setAccessible(true);
    $this->assertSame(getenv('ENV_DB_NAME'), $reflectedDB->getProperty('dbName')->getValue($database));
  }
  /**
   * @coversNothing
   * @doesNotPerformAssertions
   */
  private function getObjectProtectedProperty(string $property)
  {
    $reflectionClass = new ReflectionClass('PoolNET\config\Database');
    $reflectionProperty = $reflectionClass->getProperty($property);
    $reflectionProperty->setAccessible(true);
    return $reflectionProperty->getValue((object) $reflectionClass->newInstance());
  }
  /**
   * @covers \PoolNET\config\database\Database::connect
   * @uses \PoolNET\config\Env
   */
  public function testConnect(): void
  {
    // Testejant l'èxit
    $database = new Database();
    $dbcnx = $database->connect();
    $this->assertInstanceOf(PDO::class, $dbcnx);

    // Testejant l'error
    // TODO: Testejar el throw
    // $reflectedDB = new ReflectionClass('PoolNET\config\Database');
    // $instance = (object) $reflectedDB->newInstance();
    // $reflectedDB->getProperty('dbName')->setValue($instance, 'invalidHost');
    // $dbcnx2 = $instance->connect();
    // $this->expectOutputRegex('/^Database connection failed:/');
    // $this->assertNull($dbcnx2);

  }
}
