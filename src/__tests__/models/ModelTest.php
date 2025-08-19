<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PoolNET\Model;
use PoolNET\config\Env;

/**
 * @covers \PoolNET\Model
 * @uses \PoolNET\config\database\Database
 * @uses \PoolNET\config\Env
 */
class ModelTest extends TestCase
{
  static $mockedExtension;
  public function setUp(): void
  {
    Env::executar();
    $this->mockedExtension = new class extends Model
    {
      protected static string $table = "mockedTable";
      protected static string $idKey = "mockId";
      protected static array $uniqueKeyValues = ["mockId"];
      public ?int $mockId = null;
      public ?string $value1 = null;
      public string $value2;
    };
  }
  /**
   * @covers \PoolNET\Model::__construct
   */
  public function testConstructorWithNoData(): void
  {
    $model = $this->getMockBuilder(Model::class)->getMock();
    $this->assertInstanceOf(Model::class, $model);
    // $this->assertNull($model->dbcnx);
  }
  /**
   * @covers \PoolNET\Model::__construct
   */
  public function testConstructorWithData(): void
  {
    $mockedModel = new $this->mockedExtension(["value1" => "value1", "value2" => "value2"]);
    $this->assertInstanceOf(Model::class, $mockedModel);
    $this->assertSame("value1", $mockedModel->value1);
    $this->assertSame("value2", $mockedModel->value2);
    $this->assertNull($mockedModel->mockId);
  }
  /**
   * @covers \PoolNET\Model::connect
   * @uses \PoolNET\config\database\Database
   */
  public function testConnect(): void
  {
    $mockedModel = new $this->mockedExtension();
    $this->assertInstanceOf(Model::class, $mockedModel);
    $reflectedModel = new ReflectionClass(Model::class);

    $dbcnxProp = $reflectedModel->getProperty('dbcnx');
    $dbcnxProp->setAccessible(true);

    $connectMethod = $reflectedModel->getMethod('connect');
    $connectMethod->setAccessible(true);
    $connectMethod->invoke($mockedModel);
    // $dbcnx is instance of PDO after connecting
    $this->assertNotNull($dbcnxProp->getValue());
    $this->assertInstanceOf(PDO::class, $dbcnxProp->getValue());
  }
}
