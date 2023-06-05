<?php
require_once __DIR__ . '/../config/env.php';
require_once __DIR__ . '/../database/UserDB.php';
class User extends UserDB
{
  // Properties
  public ?int $userID;
  public ?string $usuari;
  private ?string $email;
  private ?string $salt;
  private ?string $hash;
  public ?int $nivell;
  protected ?string $data_creacio;

  public function __construct(?array $data = null)
  {
    if ($data != null) {
      foreach ($data as $key => $value) {
        if (property_exists($this, $key)) $this->$key = $value;
      }
    }
  }

  public function getPrivateEmail()
  {
    return $this->email;
  }

  public function getUserBy(string $uniqueKey, $queryId = null)
  {
    if (!in_array($uniqueKey, parent::$uniqueKeyValues)) return false;
    if ($queryId === null) {
      if ($this->{$uniqueKey} === null) return false;
      $queryId = $this->{$uniqueKey};
    }
    $userPerId = parent::trobarPer($uniqueKey, $queryId);
    if ($userPerId['success']) {
      $this->userID = $userPerId['data']['userID'];
      $this->usuari = $userPerId['data']['usuari'];
      $this->email = $userPerId['data']['email'];
      $this->nivell = $userPerId['data']['nivell'];
      $this->data_creacio = $userPerId['data']['data_creacio'];
      $this->hash = $userPerId['data']['hash'];
      $this->salt = $userPerId['data']['salt'];
      return true;
    }
    return false;
  }

  public function checkPswd($password)
  {
    $hash2 = md5(getenv('ENV_ServerSalt') . $this->salt . $password);
    return $hash2 == $this->hash;
  }
}
