<?php
include_once __DIR__ . '/../config/env.php';
class User
{
  // DB
  private $dbcnx;
  private $table = 'user';

  // Properties
  public $userID;
  public $usuari;
  public $email;
  private $salt;
  private $hash;
  public $nivell;
  public $data_creacio;

  public function __construct($db)
  {
    $this->dbcnx = $db;
  }

  public function getUserByName()
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE usuari = :usuari';
    $stmt = $this->dbcnx->prepare($query);
    $stmt->bindParam(':usuari', $this->usuari);
    $stmt->execute();
    
    if($stmt->rowCount() == 0) return false;

    $singleUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $this->userID = $singleUser['userID'];
    $this->email = $singleUser['email'];
    $this->nivell = $singleUser['nivell'];
    $this->data_creacio = $singleUser['data_creacio'];
    $this->hash = $singleUser['hash'];
    $this->salt = $singleUser['salt'];

    return true;
  }

  public function getUserById()
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE userID = :userID';
    $stmt = $this->dbcnx->prepare($query);
    $stmt->bindParam(':userID', $this->userID, PDO::PARAM_INT);
    $stmt->execute();
    
    if($stmt->rowCount() == 0) return false;

    $singleUser = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->usuari = $singleUser['usuari'];
    $this->email = $singleUser['email'];
    $this->nivell = $singleUser['nivell'];
    $this->data_creacio = $singleUser['data_creacio'];
    $this->hash = $singleUser['hash'];
    $this->salt = $singleUser['salt'];

    return true;
  }

  public function checkPswd($password) {
    $hash2 = md5(getenv('ENV_ServerSalt').$this->salt.$password);
    return $hash2 == $this->hash;
  }
}
