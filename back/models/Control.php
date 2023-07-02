<?php
namespace PoolNET;

class Control extends Model
{
  protected static string $table = 'piscinaControl';
  protected static string $idKey = 'controlID';
  protected static array $uniqueKeyValues = ['controlID'];

  // Properties
  public ?int $controlID;
  public ?string $data_hora;
  public ?float $ph;
  public ?float $clor;
  public ?float $alcali;
  public ?int $temperatura;
  public ?int $transparent;
  public ?int $fons;
  public ?int $usuari; // Fa referencia a l'Id
  public ?User $user;

  public function __construct( ? array $data = null)
  {
    parent::__construct($data);
    if (isset($this->usuari)) {
      $this->getDadesUsuari();
    }
  }

  // MÈTOODES ESTÀTICS CRUD
  // MÈTODES NO-ESTÀTICS CRUD

  public function desar()
  {
    $arrayControl = get_object_vars($this);
    $arrayControl = $this->estandard($arrayControl);
    return parent::crear($arrayControl);
  }

  // GETTERS
  public function getDadesUsuari()
  {
    if ($this->usuari == null) {
      return false;
    }

    $this->user = User::trobarPerId($this->usuari);
    return true;
  }

  // ALTRES MÈTODES
  /**
   * Estandarditza les propietats de l'objecte per a ser creat. Eliminar aquelles columnes que tenen valor per defecte a la DB. O bé la propietat user que és l'objecte relacionat..
   */
  private function estandard(array $data)
  {
    unset($data['controlID']);
    unset($data['data_hora']);
    unset($data['user']);
    return $data;
  }

  /**
   * Comprova si totes les propietats de l'objecte són null. Per evitar desar objectes nul a la DB
   * @return bool
   */
  public function allNull()
  {
    foreach (get_object_vars($this) as $propietat => $valor) {
      if ($propietat != 'usuari') {
        if (!is_null($valor)) {
          return false;
        }

      }
    }
    return true;
  }
}
