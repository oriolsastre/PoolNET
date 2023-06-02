<?php
require_once __DIR__ . '/../database/ControlDB.php';
class Control extends ControlDB
{
  // Properties
  public ?int $controlID;
  public ?string $data_hora;
  public ?float $ph;
  public ?float $clor;
  public ?float $alcali;
  public ?int $temperatura;
  public ?int $transparent;
  public ?int $fons;
  public ?int $usuari;   // Fa referencia a l'Id
  public ?User $user;

  public function __construct(?array $data = null)
  {
    if ($data != null) {
      foreach ($data as $key => $value) {
        if (property_exists($this, $key)) $this->$key = $value;
      }
    }
  }

  /* public function read_single(){
    $query = 'SELECT * FROM ' . $this->table . ' WHERE controlID = ?';

    // Prepare statement
    $stmt = $this->dbcnx->prepare($query);

    // Bind ? to value
    $stmt->bindParam(1, $this->controlID);

    // Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  } */

  public function desar()
  {
    return parent::crear($this);
  }

  /**
   * Comprova si totes les propietats de l'objecte són null. Per evitar desar objectes nul a la DB
   * @return bool
   */
  public function allNull()
  {
    foreach (get_object_vars($this) as $propietat => $valor) {
      if ($propietat != 'usuari') {
        if (!is_null($valor)) return false;
      }
    }
    return true;
  }
}
