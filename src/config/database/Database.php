<?php

namespace PoolNET\config\database;

use PDO;
use PDOException;
use PoolNET\interface\config\Database as DatabaseInterface;

class Database implements DatabaseInterface
{
  private string $dbName;
  private ?PDO $dbcnx;

  public function __construct()
  {
    $this->dbName = (string) getenv('ENV_DB_NAME');
  }
  /**
   * Connecta a la base de dades
   * @return PDO La connexió a la base de dades
   * @throws PDOException
   */
  public function connect(): PDO | null
  {
    // try {
    $this->dbcnx = new PDO(
      // 'mysql:host=' . $this->host . ';dbname=' . $this->dbName,
      dsn: 'sqlite:' . __DIR__ . '/' . $this->dbName . '.db',
      options: [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION] // Això per poder capturar errors diria.
    );
    // } catch (PDOException $err) {
    // echo 'Database connection failed: ' . $err->getMessage();
    // return null;
    // }
    return $this->dbcnx;
  }
}
