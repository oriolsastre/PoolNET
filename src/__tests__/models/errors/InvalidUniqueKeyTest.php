<?php declare (strict_types = 1);
use PHPUnit\Framework\TestCase;
use PoolNET\error\InvalidUniqueKey;

/**
 * @covers \PoolNET\error\InvalidUniqueKey
 */
class InvalidUniqueKeyTest extends TestCase
{
  /**
   * @covers \PoolNET\error\InvalidUniqueKey::__construct
   */
  public function testConstructor(): void
  {
    $exception = new InvalidUniqueKey();
    $this->assertSame('Invalid unique key', $exception->getMessage());
    $this->assertSame(400, $exception->getCode());
  }
}
