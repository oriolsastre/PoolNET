<?php

namespace PoolNET;

use PoolNET\config\Env;

class User extends Model
{
  protected static string $table = 'user';
  protected static string $idKey = 'userID';
  protected static array $uniqueKeyValues = ['userID', 'usuari', 'email'];

  // Properties
  public int $userID;
  public string $usuari;
  protected string $email;
  protected string $salt;
  protected string $hash;
  public int $nivell;
  protected string $data_creacio;

  // MÈTODES ESTÀTICS CRUD
  // MÈTOODES NO-ESTÀTICS CRUD
  // GETTERS
  /**
   * Retorna l'email de l'usuari.
   * @return string Email de l'usuari
   */
  public function getPrivateEmail(): string
  {
    return $this->email;
  }
  // ALTRES MÈTODES
  /**
   * Comprova si la contrassenya és vàlida.
   * @param string $password Contrassenya.
   * @return bool `true` si la contrassenya és vàlida, `false` en cas contrari.
   */
  public function checkPswd(string $password): bool
  {
    Env::executar();
    $hash2 = md5(getenv('ENV_ServerSalt') . $this->salt . $password);
    return $hash2 === $this->hash;
  }
}
