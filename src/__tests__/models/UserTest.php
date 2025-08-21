<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PoolNET\User;
use PoolNET\config\Env;

/**
 * @covers \PoolNET\User
 * @uses \PoolNET\Model
 */
class UserTest extends TestCase
{
    private string $testEmail = 'test@example.com';
    private int $testNivell = 2;
    private string $testSalt = 'testSalt';
    private string $testPassword = 'testPassword';
    public function testConstructorWithNoData(): void
    {
        $user = new User();
        $this->assertInstanceOf(User::class, $user);
        $reflectUser = new ReflectionObject($user);
        $reflectUser->getProperty('table')->setAccessible(true);
        $reflectUser->getProperty('idKey')->setAccessible(true);
        $reflectUser->getProperty('uniqueKeyValues')->setAccessible(true);
        $this->assertSame('usuari', $reflectUser->getProperty('table')->getValue($user));
        $this->assertSame('usuariId', $reflectUser->getProperty('idKey')->getValue($user));
        $this->assertSame(['usuariId', 'usuari', 'email'], $reflectUser->getProperty('uniqueKeyValues')->getValue($user));
    }

    public function testGetPrivateEmail(): void
    {
        $data = [
            'email' => $this->testEmail,
        ];
        $user = new User($data);
        $this->assertSame($this->testEmail, $user->getPrivateEmail());
    }

    public function testGetNivell(): void
    {
        $data = [
            'nivell' => $this->testNivell,
        ];
        $user = new User($data);
        $this->assertSame($this->testNivell, $user->getNivell());
    }

    /**
     * @uses \PoolNET\config\Env
     */
    public function testCheckPswd(): void
    {
        Env::executar();
        $hash = md5(getenv('ENV_ServerSalt') . $this->testSalt . $this->testPassword);
        $data = [
            'email' => $this->testEmail,
            'nivell' => $this->testNivell,
            'salt' => $this->testSalt,
            'hash' => $hash
        ];
        $user = new User($data);
        $this->assertTrue($user->checkPswd($this->testPassword));
        $this->assertFalse($user->checkPswd($this->testPassword . 'extra'));
    }
}
