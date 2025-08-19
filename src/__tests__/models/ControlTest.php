<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PoolNET\Control;
use PoolNET\config\Env;

/**
 * @covers \PoolNET\Control
 * @uses \PoolNET\Model
 * @uses \PoolNET\config\database\Database
 * @uses \PoolNET\config\Env
 */
class ControlTest extends TestCase
{
  public function setUp(): void
  {
    Env::executar();
  }
  /**
   * @covers \PoolNET\Control::__construct
   * @cover \PoolNET\Model::__construct
   */
  public function testConstructorWithNoData(): void
  {
    $control = new Control();
    $this->assertInstanceOf(Control::class, $control);
    $reflectControl = new ReflectionObject($control);
    $reflectControl->getProperty('table')->setAccessible(true);
    $this->assertSame('control', $reflectControl->getProperty('table')->getValue($control));
    $reflectControl->getProperty('idKey')->setAccessible(true);
    $this->assertSame('controlId', $reflectControl->getProperty('idKey')->getValue($control));
    $reflectControl->getProperty('uniqueKeyValues')->setAccessible(true);
    $this->assertSame(['controlId'], $reflectControl->getProperty('uniqueKeyValues')->getValue($control));
  }
  /**
   * @covers \PoolNET\Control::__construct
   * @covers \PoolNET\Model::__construct
   * @uses \PoolNET\Model
   */
  public function testConstructorWithDataNoUser(): void
  {
    $data = [
      'data_hora' => date('Y-m-d H:i:s'),
      'ph' => 6.8,
      'clor' => 0.1,
      'temperatura' => 31,
      'transparent' => 1,
      'fons' => 1,
    ];
    $control = new Control($data);
    $this->assertInstanceOf(Control::class, $control);
    $this->assertNull($control->controlId);
    $this->assertSame($data['data_hora'], $control->data_hora);
    $this->assertSame($data['ph'], $control->ph);
    $this->assertSame($data['clor'], $control->clor);
    $this->assertSame($data['temperatura'], $control->temperatura);
    $this->assertSame($data['transparent'], $control->transparent);
    $this->assertSame($data['fons'], $control->fons);
    $this->assertNull($control->usuari);
    $this->assertNull($control->user);
  }
  /**
   * @covers \PoolNET\Control::__construct
   * @covers \PoolNET\Model::__construct
   * @uses \PoolNET\User
   * @uses \PoolNET\Control::getDadesUsuari
   */
  public function testConstructorWithDataAndUser(): void
  {
    $data = [
      'data_hora' => date('Y-m-d H:i:s'),
      'ph' => 6.8,
      'clor' => 0.1,
      'temperatura' => 31,
      'transparent' => 1,
      'fons' => 1,
      'usuari' => 1,
    ];
    $control = new Control($data);
    $this->assertInstanceOf(Control::class, $control);
    $this->assertNull($control->controlId);
    $this->assertSame($data['data_hora'], $control->data_hora);
    $this->assertSame($data['ph'], $control->ph);
    $this->assertSame($data['clor'], $control->clor);
    $this->assertSame($data['temperatura'], $control->temperatura);
    $this->assertSame($data['transparent'], $control->transparent);
    $this->assertSame($data['fons'], $control->fons);
    $this->assertSame($data['usuari'], $control->usuari);
    $this->assertInstanceOf('PoolNET\User', $control->user);
    $this->assertSame($data['usuari'], $control->user->usuariId);
  }
  /**
   * @covers \PoolNET\Control::getDadesUsuari
   * @uses \PoolNET\User
   */
  public function testGetDadesUsuari(): void
  {
    $control = new Control();
    $this->assertNull($control->usuari);
    $this->assertNull($control->user); // És null abans
    $this->assertFalse($control->getDadesUsuari());
    $this->assertNull($control->user); // És null després

    $control->usuari = 1;
    $this->assertTrue($control->getDadesUsuari());
    $this->assertInstanceOf('PoolNET\User', $control->user);
    $this->assertSame(1, $control->user->usuariId);

    $control->usuari = 90; //No hauria d'existir
    $this->assertFalse($control->getDadesUsuari());
  }
  /**
   * @covers \PoolNET\Control::allNull
   */
  public function testAllNull(): void
  {
    $control = new Control();
    $this->assertTrue($control->allNull());

    $control->controlId = 1;
    $control->usuari = 1;
    $control->getDadesUsuari();
    $this->assertNotNull($control->controlId);
    $this->assertNotNull($control->usuari);
    $this->assertNotNull($control->user);
    $this->assertNotNull($control->data_hora);
    $this->assertTrue($control->allNull());

    $control->ph = 7;
    $this->assertFalse($control->allNull());
  }
}
