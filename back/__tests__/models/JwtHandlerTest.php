<?php declare (strict_types = 1);
use PHPUnit\Framework\TestCase;
use PoolNET\JwtHandler;

class JwtHandlerTest extends TestCase
{
  public function testJwtEncodeData()
  {
    $jwtHandler = new JwtHandler();
    $data = ['foo' => 'bar'];
    $token = $jwtHandler->jwtEncodeData('poolnet', $data);
    $this->assertIsString($token);
  }
}
