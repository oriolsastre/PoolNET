<?php
class Control
{
  // DB
  private $dbcnx;
  private $table = 'piscinaControl';

  // Properties
  public $controlID;
  public $data_hora;
  public $ph;
  public $clor;
  public $alcali;
  public $temperatura;
  public $transparent;
  public $fons;
  public $usuari;
  // public $usuari_nom;

  public function __construct($db)
  {
    $this->dbcnx = $db;
  }

  // GET controls
  public function read($limit=20)
  {
    $query = 'SELECT * FROM ' . $this->table . ' ORDER BY data_hora DESC LIMIT ' . $limit;

    // Prepare statement
    $stmt = $this->dbcnx->prepare($query);

    // Execute query
    $stmt->execute();

    return $stmt;
  }

  public function read_single(){
    $query = 'SELECT * FROM ' . $this->table . ' WHERE controlID = ?';

    // Prepare statement
    $stmt = $this->dbcnx->prepare($query);

    // Bind ? to value
    $stmt->bindParam(1, $this->controlID);

    // Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function create(){
    $query = 'INSERT INTO ' . $this->table . ' SET
      ph = :ph,
      clor = :clor,
      alcali = :alcali,
      temperatura = :temperatura,
      transparent = :transparent,
      fons = :fons,
      usuari = :usuari';
    
    // Prepare statement
    $stmt = $this->dbcnx->prepare($query);

    // Clean data
    if($this->transparent != null) $this->transparent = intval($this->transparent);
    if($this->temperatura != null) $this->temperatura = intval($this->temperatura);
    if($this->fons != null) $this->fons = intval($this->fons);
    $this->usuari = intval($this->usuari);

    // Bind data
    $stmt->bindParam(':ph', $this->ph);
    $stmt->bindParam(':clor', $this->clor);
    $stmt->bindParam(':alcali', $this->alcali);
    $stmt->bindParam(':transparent', $this->transparent);
    $stmt->bindParam(':temperatura', $this->temperatura);
    $stmt->bindParam(':fons', $this->fons);
    $stmt->bindParam(':usuari', $this->usuari);

    if($stmt->execute()){
      return true;
    }
    // Tractar errors
    printf("Error: %s.\n", $stmt->error);

    return false;
  }
}
