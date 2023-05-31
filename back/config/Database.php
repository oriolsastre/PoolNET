<?php
include __DIR__ . '/../config/env.php';
class Database
{
  // Params
  private $host;
  private $db_name;
  private $user;
  private $password;
  private $dbcnx;

  public function __construct()
  {
   $this->host = getenv('ENV_DB_HOST');
   $this->db_name = getenv('ENV_DB_NAME');
   $this->user = getenv('ENV_DB_USER');
   $this->password = getenv('ENV_DB_PSWD');
  }
  // DB connect
  public function connect()
  {
    $this->dbcnx = null;
    try {
      $this->dbcnx = new PDO(
        'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
        $this->user,
        $this->password
      );
      // Això per poder capturar errors diria.
      $this->dbcnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $err) {
      echo 'Database connection failed: ' . $err->getMessage();
    }

    return $this->dbcnx;
  }
}
