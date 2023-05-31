<?php
class Accio
{

  private $dbcnx;
  private $table = 'piscinaAccio';

  public $accioID;
  public $data_hora;
  public $ph;
  public $clor;
  public $antialga;
  public $fluoculant;
  public $aspirar;
  public $alcali;
  public $aglutinant;
  public $usuari;

  public function __construct($db)
  {
    $this->dbcnx = $db;
  }

  public function read($limit = 20)
  {
    $query = 'SELECT * FROM ' . $this->table . ' ORDER BY data_hora DESC LIMIT ' . $limit;

    // Prepare statement
    $stmt = $this->dbcnx->prepare($query);
    // Execute query
    $stmt->execute();

    return $stmt;
  }

  public function create()
  {
    $query = 'INSERT INTO ' . $this->table . ' SET
      ph = :ph,
      clor = :clor,
      antialga = :antialga,
      fluoculant = :fluoculant,
      aspirar = :aspirar,
      alcali = :alcali,
      aglutinant = :aglutinant,
      usuari = :usuari';

    $stmt = $this->dbcnx->prepare($query);
    // Clean data
    $this->ph = intval($this->ph);
    $this->clor = intval($this->clor);
    $this->antialga = intval($this->antialga);
    $this->fluoculant = intval($this->fluoculant);
    $this->aspirar = intval($this->aspirar);
    $this->alcali = intval($this->alcali);
    $this->aglutinant = intval($this->aglutinant);
    $this->usuari = intval($this->usuari);
    // Bind data
    $stmt->bindParam(':ph', $this->ph);
    $stmt->bindParam(':clor', $this->clor);
    $stmt->bindParam(':antialga', $this->antialga);
    $stmt->bindParam(':fluoculant', $this->fluoculant);
    $stmt->bindParam(':aspirar', $this->aspirar);
    $stmt->bindParam(':alcali', $this->alcali);
    $stmt->bindParam(':aglutinant', $this->aglutinant);
    $stmt->bindParam(':usuari', $this->usuari);
    // Execute query
    if ($stmt->execute()) {
      return true;
    }
    printf("Error: %s.\n", $stmt->error);
    return false;
  }
}
