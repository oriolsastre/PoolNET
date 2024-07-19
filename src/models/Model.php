<?php
namespace PoolNET;

use Exception;
use PDO;
use PoolNET\config\Database;
use PoolNET\error\InvalidUniqueKey;

/**
 * Classe abastracta model. Mètodes per connectar un model a la base de dades.
 * @private ?PDO $dbcnx Connexió a la base de dades.
 * @protected string $table Nom de la taula.
 * @protected string $idKey Identificador de la clau primaria.
 * @protected array<string> $uniqueKeyValues Camps únics de la taula.
 */
abstract class Model
{
  private static ?PDO $dbcnx = null;
  protected static string $table;
  protected static string $idKey;
  protected static array $uniqueKeyValues;

  /**
   * Constructor
   * @param array<string, mixed> $data
   */
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

  /**
   * Connecta a la base de dades.
   * @return void
   */
  private static function connect() : void
  {
    $database = new Database();
    self::$dbcnx = $database->connect();
  }
  // CRUD
  // CREAR
  /**
   * Crea una fila de la base de dades. S'ha de cridar des d'una subclasse.
   * @param array<string, mixed> $data Valors de la fila.
   * @return bool ``true`` si s'ha creat correctament, ``false`` en cas contrari.
   */
  protected static function crear(array $data): bool
  {
    if (static::$dbcnx == null) {
      self::connect();
    }
    $valors = implode(", ", array_map(function (string $columna, $valor) {
      if ($valor === null) {
        return "$columna = NULL";
      }
      return "$columna = \"$valor\"";
    }, array_keys($data), $data));

    $query = 'INSERT INTO ' . static::$table . ' SET ' . $valors . ';';
    $stmt = self::$dbcnx->prepare($query);
    return $stmt->execute();
  }
  // READ/LLEGIR
  /**
   * Troba una fila de la base de dades per un identificador únic.
   * @param string $uniqueKey Identificador únic.
   * @param mixed $id Valor
   * @return static|null|false Si existeix, retorna una instància de la classe, ``null`` en cas contrari. Retorna ``false`` si falla alguna cosa.
   * @throws InvalidUniqueKey Si no existeix l'identificador únic.
   */
  public static function trobarPerUnic(string $uniqueKey, mixed $id): static  | null | false
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
    if ($stmt->execute()) {
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      return $data == null ? null : new static($data);
    }
    return false;
  }
  /**
   * Troba una fila a la base de dades per id.
   * @param int $id Identificador.
   * @return static|null|false Si existeix, retorna una instància de la classe, ``null`` en cas contrari. Retorna ``false`` si falla alguna cosa.
   */
  public static function trobarPerId(int $id): static  | null | false
  {
    return self::trobarPerUnic(static::$idKey, $id);
  }
  /**
   * Troba tots aquells valors que compleixin les condifions especificades.
   * @param array{?where: array, ?orderBy: array}|null $condicions Condicions a trobar.
   * @param array<string, mixed> $condicions['where'] ``['columna' => 'valor']``. Només condicions AND.
   * @param array<string, ASC|DESC> $condicions['orderBy'] ``['columna', 'direcció']``. De moment només 1 columns.
   * @param int $limit Número de resultats a obtenir. Per defecte 20.
   * @return static[]|null Array d'instàncies que compleixen les condicions.
   * @throws Exception Si falla alguna cosa.
   */
  public static function trobarMolts( ? array $condicions = null, int $limit = 20) :  ? array
  {
    if (static::$dbcnx === null) {
      self::connect();
    }

    $query = 'SELECT * FROM ' . static::$table;
    if ($condicions !== null) {
      if (isset($condicions['where'])) {
        $query .= ' WHERE ' . implode(" AND ", array_map(function (string $columna, $valor) {
          if ($valor === null) {
            return "$columna IS NULL";
          }
          return "$columna = \"$valor\"";
        }, array_keys($condicions['where']), $condicions['where']));
      }
      if (isset($condicions['orderBy'])) {
        $query .= ' ORDER BY ' . $condicions['orderBy'][0] . ' ' . $condicions['orderBy'][1];
      }

      // Si hi ha condicions i no s'especifica límit, el límit passa a ser 1000 (com si no n'hi hagués per mostrar-los tots, però per seguretat limitat)
      if (func_num_args() === 1) {$limit = 1000;}
    }
    if ($limit > 0) {
      $query .= ' LIMIT ' . $limit;
    }
    $stmt = self::$dbcnx->prepare($query);
    if ($stmt->execute()) {
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $data === null ? [] : array_map(function ($modelObject) {
        return new static($modelObject);
      }, $data);
    }
    throw new Exception('Error obtenint les dades. Alguna clàusula no deu ser correcta.', 400);
  }
  // UPDATE/ACTUALITZAR
  /**
   * Actualitza una fila de la base de dades per un identificador únic.
   * @param array<string, mixed> $data Valors de la fila a actualizar.
   * @param string $uniqueKey Identificador únic.
   * @param mixed $id Valor de l'identificador únic.
   * @return bool ``true`` si s'ha actualitzat correctament, ``false`` en cas contrari.
   * @throws InvalidUniqueKey Si no existeix l'identificador únic.
   */
  private static function updatePerUnic(array $data, string $uniqueKey, $id) : bool
  {
    if (!in_array($uniqueKey, static::$uniqueKeyValues)) {
      throw new InvalidUniqueKey();
    }
    if (self::$dbcnx == null) {
      self::connect();
    }
    $valors = implode(", ", array_map(function (string $columna, $valor) {
      if ($valor === null) {
        return "$columna = NULL";
      }
      return "$columna = \"$valor\"";
    }, array_keys($data), $data));
    $query = 'UPDATE ' . static::$table . ' SET ' . $valors . ' WHERE ' . $uniqueKey . ' = :id';
    $stmt = self::$dbcnx->prepare($query);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
  }
  /**
   * Actualitza una fila de la base de dades per id.
   * @param array<string, mixed> $data Valors de la fila a actualizar.
   * @param int $id Id.
   * @return bool ``true`` si s'ha actualitzat correctament, ``false`` en cas contrari.
   */
  protected static function updatePerId(array $data, int $id): bool
  {
    return self::updatePerUnic($data, static::$idKey, $id);
  }
  // DELETE/BORRAR
  /**
   * Borra una fila de la base de dades per un identificador únic.
   * @param string $uniqueKey Identificador únic.
   * @param mixed $id Valor de l'identificador únic.
   * @return bool ``true`` si s'ha eliminat correctament, ``false`` en cas contrari.
   * @throws InvalidUniqueKey Si no existeix l'identificador únic.
   */
  private static function borrarPerUnic(string $uniqueKey, $id): bool
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
    return $stmt->execute();
  }
  /**
   * Borra una fila de la base de dades per id.
   * @param int $id Id.
   * @return bool ``true`` si s'ha eliminat correctament, ``false`` en cas contrari.
   */
  private static function borrarPerId(int $id): bool
  {
    return self::borrarPerUnic(static::$idKey, $id);
  }
  /**
   * Borra una fila de la base de dades corresponen a la instància que crida el mètode.
   * @return bool ``true`` si s'ha eliminat correctament, ``false`` en cas contrari.
   */
  public function borrar(): bool
  {
    return self::borrarPerId($this->{static::$idKey});
  }
}
