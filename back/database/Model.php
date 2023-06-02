<?php
require_once __DIR__ . '/../config/Database.php';

class Model
{
  private static $dbcnx;

  private static function connect()
  {
    $database = new Database();
    self::$dbcnx = $database->connect();
  }

  protected static function createDB(string $table, array $data)
  {
    if (self::$dbcnx == null) self::connect();

    $valors = implode(", ", array_map(function ($k, $v) {
      if ($v === null) return "$k = NULL";
      return "$k = $v";
    }, array_keys($data), $data));
    // $valors = rtrim($valors, ", ");

    $query = 'INSERT INTO ' . $table . ' SET ' . $valors . ';';
    $stmt = self::$dbcnx->prepare($query);
    if ($stmt->execute()) {
      return ["success" => true];
    }
    printf("Error: %s.\n", $stmt->error);
    return ["success" => false];
  }

  protected static function findById(string $table, string $idKey, $id)
  {
    if (self::$dbcnx == null) self::connect();
    $query = 'SELECT * FROM ' . $table . ' WHERE ' . $idKey . ' = :id';
    $stmt = self::$dbcnx->prepare($query);
    $stmt->bindParam(':id', $id);
    // Execute query
    if ($stmt->execute()) {
      return [
        "success" => true,
        "data" => $stmt->fetch(PDO::FETCH_ASSOC)
      ];
    }
    printf("Error: %s.\n", $stmt->error);
    return [
      "success" => false,
      "data" => null
    ];
  }

  protected static function findAll(string $table, ?array $where, int $limit = 20)
  {
    if (self::$dbcnx == null) self::connect();
    $query = 'SELECT * FROM ' . $table;
    // Si hi ha condició where, el límit passa a ser 1000 (com si no n'hi hagués, però per seguretat limitat)
    if ($where != null) {
      $query .= ' WHERE ' . implode(' AND ', $where);
      if(func_num_args() === 2) $limit=1000;
    }
    if ($limit > 0) $query .= ' LIMIT ' . $limit;
    $stmt = self::$dbcnx->prepare($query);
    if ($stmt->execute()) {
      return [
        "success" => true,
        "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)
      ];
    }
    printf("Error: %s.\n", $stmt->error);
    return [
      "success" => false,
      "data" => null
    ];
  }
}
