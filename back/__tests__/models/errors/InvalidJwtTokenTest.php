<?php declare (strict_types = 1);
use PHPUnit\Framework\TestCase;
use PoolNET\error\InvalidJwtToken;

/**
 * @covers \PoolNET\error\InvalidJwtToken
 */
class InvalidJwtTokenTest extends TestCase
{
  /**
   * @covers \PoolNET\error\InvalidJwtToken::__construct
   */
  public function testConstructor(): void
  {
    $exception = new InvalidJwtToken();
    $this->assertSame('Invalid JWT token', $exception->getMessage());
    $this->assertSame(400, $exception->getCode());
  }
}
