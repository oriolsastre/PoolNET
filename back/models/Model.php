<?php
namespace PoolNET;

use Exception;
use PDO;
use PoolNET\config\Database;
use PoolNET\config\InvalidUniqueKey;

abstract class Model
{
  private static ?PDO $dbcnx = null;
  protected static string $table;
  protected static string $idKey;
  protected static array $uniqueKeyValues;

  public function __construct( ? array $data = null)
  {
    if ($data != null) {
      foreach ($data as $key => $value) {
        if (property_exists($this, $key)) {
          $this->$key = $value;
        }
      }
    }
  }

  private static function connect()
  {
    $database = new Database();
    self::$dbcnx = $database->connect();
  }

  // CRUD
  // CREAR
  protected static function crear(array $data)
  {
    if (static::$dbcnx == null) {
      self::connect();
    }

    $valors = implode(", ", array_map(function ($k, $v) {
      if ($v === null) {
        return "$k = NULL";
      }

      return "$k = $v";
    }, array_keys($data), $data));

    $query = 'INSERT INTO ' . static::$table . ' SET ' . $valors . ';';
    $stmt = self::$dbcnx->prepare($query);
    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  // READ/LLEGIR
  /**
   * Troba una fila de la base de dades per un identificador únic.
   * @param string $idKey Identificador únic.
   * @param int $id Valor
   * @return ?static
   */
  public static function trobarPerUnic(string $uniqueKey, $id)
  {
    if (!in_array($uniqueKey, static::$uniqueKeyValues)) {
      throw new InvalidUniqueKey();
    }

    if (self::$dbcnx == null) {
      self::connect();
    }

    $query = 'SELECT * FROM ' . static::$table . ' WHERE ' . $uniqueKey . ' = :id';
    $stmt = self::$dbcnx->prepare($query);
    $stmt->bindParam(':id', $id);
    // Execute query
    if ($stmt->execute()) {
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      return $data == null ? null : new static($data);
    }
    return false;
  }
  public static function trobarPerId(int $id)
  {
    return self::trobarPerUnic(static::$idKey, $id);
  }
  /**
   * Troba tots aquells valors que compleixin les condifions especificades.
   * @param array $conditions ['where', 'orderBy'] Condicions a trobar.
   * @param array $conditions['where'] = ['columna' => 'valor'] Només condicions AND.
   * @param array $conditions['orderBy'] = ['columna', 'direcció'] ASC/DESC
   * @param int $limit Quants resultats a obtenir.
   * @return ?static[]
   */
  public static function trobarMolts( ? array $condicions = null, int $limit = 20)
  {
    if (static::$dbcnx == null) {
      self::connect();
    }

    $query = 'SELECT * FROM ' . static::$table;
    if ($condicions != null) {
      if (isset($condicions['where'])) {
        $query .= ' WHERE ' . implode(", ", array_map(function ($k, $v) {
          if ($v === null) {
            return "$k = NULL";
          }
          return "$k = $v";
        }, array_keys($condicions['where']), $condicions['where']));
      }
      if (isset($condicions['orderBy'])) {
        $query .= ' ORDER BY ' . $condicions['orderBy'][0] . ' ' . $condicions['orderBy'][1];
      }

      // Si hi ha condicions i no s'especifica límit, el límit passa a ser 1000 (com si no n'hi hagués per mostrar-los tots, però per seguretat limitat)
      if (func_num_args() === 1) {
        $limit = 1000;
      }

    }
    if ($limit > 0) {
      $query .= ' LIMIT ' . $limit;
    }

    $stmt = self::$dbcnx->prepare($query);
    if ($stmt->execute()) {
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $data == null ? [] : array_map(function ($modelObject) {
        return new static($modelObject);
      }, $data);
    }
    throw new Exception('Error obtenint les dades. Alguna clàusula no deu ser correcta.', 400);
  }

  // UPDATE/ACTUALITZAR
  private static function updatePerUnic(array $data, string $uniqueKey, $id)
  {
    if (!in_array($uniqueKey, static::$uniqueKeyValues)) {
      throw new InvalidUniqueKey();
    }
    if (self::$dbcnx == null) {
      self::connect();
    }

    $valors = implode(", ", array_map(function ($k, $v) {
      if ($v === null) {
        return "$k = NULL";
      }
      return "$k = \"$v\"";
    }, array_keys($data), $data));
    $query = 'UPDATE ' . static::$table . ' SET ' . $valors . ' WHERE ' . $uniqueKey . ' = :id';
    $stmt = self::$dbcnx->prepare($query);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
      return true;
    }
    return false;
  }
  protected static function updatePerId(array $data, int $id)
  {
    return self::updatePerUnic($data, static::$idKey, $id);
  }

  // DELETE/BORRAR
  private static function borrarPerUnic(string $uniqueKey, $id)
  {
    if (!in_array($uniqueKey, static::$uniqueKeyValues)) {
      throw new InvalidUniqueKey();
    }
    if (self::$dbcnx == null) {
      self::connect();
    }

    $query = 'DELETE FROM ' . static::$table . ' WHERE ' . $uniqueKey . ' = :id';
    $stmt = self::$dbcnx->prepare($query);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
      return true;
    }
    return false;
  }
  private static function borrarPerId(int $id)
  {
    return self::borrarPerUnic(static::$idKey, $id);
  }
  public function borrar()
  {
    return self::borrarPerId($this->{static::$idKey});
  }
}
