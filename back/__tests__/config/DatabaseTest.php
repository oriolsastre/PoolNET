<?php declare (strict_types = 1);
use PHPUnit\Framework\TestCase;
use PoolNET\config\Database;

/** 
 * @covers \PoolNET\config\Database
 */
class DatabaseTest extends TestCase
{
  /**
   * @covers \PoolNET\config\Database::__construct
   * @uses \PoolNET\config\Env
   */
  public function testConstructor(): void
  {
    $database = new Database();
    $this->assertInstanceOf(Database::class, $database);
    $reflectedDB = new ReflectionObject($database);
    $reflectedDB->getProperty('host')->setAccessible(true);
    $this->assertSame(getenv('ENV_DB_HOST'), $reflectedDB->getProperty('host')->getValue($database));
    $reflectedDB->getProperty('dbName')->setAccessible(true);
    $this->assertSame(getenv('ENV_DB_NAME'), $reflectedDB->getProperty('dbName')->getValue($database));
    $reflectedDB->getProperty('user')->setAccessible(true);
    $this->assertSame(getenv('ENV_DB_USER'), $reflectedDB->getProperty('user')->getValue($database));
    $reflectedDB->getProperty('password')->setAccessible(true);
    $this->assertSame(getenv('ENV_DB_PSWD'), $reflectedDB->getProperty('password')->getValue($database));
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
   * @covers \PoolNET\config\Database::connect
   * @uses \PoolNET\config\Env
   */
  public function testConnect(): void
  {
    // Testejant l'èxit
    $database = new Database();
    $dbcnx = $database->connect();
    $this->assertInstanceOf(PDO::class, $dbcnx);

    // Testejant l'error
    $reflectedDB = new ReflectionClass('PoolNET\config\Database');
    $instance = (object) $reflectedDB->newInstance();
    $reflectedDB->getProperty('host')->setValue($instance, 'invalidHost');
    $dbcnx2 = $instance->connect();
    $this->assertNull($dbcnx2);

  }
}