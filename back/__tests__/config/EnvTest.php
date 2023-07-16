<?php declare (strict_types = 1);
use PHPUnit\Framework\TestCase;
use PoolNET\config\Env;

/**
 * @covers \PoolNET\config\Env
 */
class EnvTest extends TestCase
{
  public function testExecutar(): void
  {
    Env::executar();
    $this->assertNotEmpty(getenv('ENV_DB_HOST'));
    $this->assertNotEmpty(getenv('ENV_DB_NAME'));
    $this->assertNotEmpty(getenv('ENV_DB_USER'));
    $ENV_DB_PSWD = getenv('ENV_DB_PSWD');
    $this->assertTrue($ENV_DB_PSWD === '' || is_string($ENV_DB_PSWD));
    $this->assertNotEmpty(getenv('ENV_HEADERS_ALLOW_ORIGIN'));
    $this->assertNotEmpty(getenv('ENV_HEADERS_ALLOW_HEADERS'));
    $this->assertNotEmpty(getenv('ENV_JWTSecret'));
    $this->assertNotEmpty(getenv('ENV_ServerSalt'));
  }
}
