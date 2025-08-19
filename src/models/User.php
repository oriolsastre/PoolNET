<?php

namespace PoolNET;

class User extends Model
{
  protected static string $table = 'usuari';
  protected static string $idKey = 'usuariId';
  protected static array $uniqueKeyValues = ['usuariId', 'usuari', 'email'];

  // Properties
  public int $usuariId;
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
    $hash2 = md5(getenv('ENV_ServerSalt') . $this->salt . $password);
    return $hash2 === $this->hash;
  }
}
