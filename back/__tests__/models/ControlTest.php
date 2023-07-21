<?php declare (strict_types = 1);
use PHPUnit\Framework\TestCase;
use PoolNET\Control;

/**
 * @covers \PoolNET\Control
 * @uses \PoolNET\Model
 * @uses \PoolNET\config\Database
 * @uses \PoolNET\config\Env
 */
class ControlTest extends TestCase
{
  /**
   * @covers \PoolNET\Control::__construct
   * @cover \PoolNET\Model::__construct
   * @uses \PoolNET\Model
   */
  public function testConstructorWithNoData(): void
  {
    $control = new Control();
    $this->assertInstanceOf(Control::class, $control);
  }
}
