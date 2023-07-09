<?php
namespace PoolNET\config;

use PDO;
use PDOException;

class Database
{
  // Params
  private string $host;
  private string $dbName;
  private string $user;
  private string $password;
  private ?PDO $dbcnx;

  public function __construct()
  {
    Env::executar();
    $this->host = (string) getenv('ENV_DB_HOST');
    $this->dbName = (string) getenv('ENV_DB_NAME');
    $this->user = (string) getenv('ENV_DB_USER');
    $this->password = (string) getenv('ENV_DB_PSWD');
  }
  /**
   * Connecta a la base de dades
   * @return PDO La connexió a la base de dades
   */
  public function connect(): PDO
  {
    try {
      $this->dbcnx = new PDO(
        'mysql:host=' . $this->host . ';dbname=' . $this->dbName,
        $this->user,
        $this->password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]// Això per poder capturar errors diria.
      );
    } catch (PDOException $err) {
      echo 'Database connection failed: ' . $err->getMessage();
      return null;
    }
    return $this->dbcnx;
  }
}
