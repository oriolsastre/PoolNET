<?php declare (strict_types = 1);
use PHPUnit\Framework\TestCase;
use PoolNET\Model;

/**
 * @covers \PoolNET\Model
 * @uses \PoolNET\config\Database
 * @uses \PoolNET\config\Env
 */
class ModelTest extends TestCase
{
  /**
   * @covers \PoolNET\Model::__construct
   */
  public function testConstructorWithNoData(): void
  {
    $model = $this->getMockBuilder(Model::class)->getMock();
    $this->assertInstanceOf(Model::class, $model);
    $this->assertNull($model->dbcnx);
  }
}
