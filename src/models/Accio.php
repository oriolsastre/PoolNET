<?php
require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/User.php';
class Accio extends Model
{
  protected static string $table = 'piscinaAccio';
  protected static string $idKey = 'accioID';
  protected static array $uniqueKeyValues = ['accioID'];

  // Properties
  public ?int $accioID;
  public ?string $data_hora;
  public ?int $ph;
  public ?int $clor;
  public ?int $antialga;
  public ?int $fluoculant;
  public ?int $aspirar;
  public ?int $alcali;
  public ?int $aglutinant;
  public ?int $usuari;
  public ?User $user;

  public function __construct(?array $data = null)
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
    $arrayAccio = get_object_vars($this);
    $arrayAccio = $this->estandard($arrayAccio);
    return parent::crear($arrayAccio);
  }

  public function borrar()
  {
    return parent::borrarPerUnic('accioID', $this->accioID);
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
    unset($data['accioID']);
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
