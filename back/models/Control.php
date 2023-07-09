<?php
namespace PoolNET;

class Control extends Model
{
  protected static string $table = 'piscinaControl';
  protected static string $idKey = 'controlID';
  protected static array $uniqueKeyValues = ['controlID'];

  // Properties
  public ?int $controlID = null;
  public string $data_hora;
  public ?float $ph;
  public ?float $clor;
  public ?float $alcali;
  public ?int $temperatura;
  public ?int $transparent;
  public ?int $fons;
  public int $usuari; // Fa referencia a l'Id
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
  /**
   * Desa a la base de dades el control actual. Si no conté ID es crearà un control nou a la base de dades. Si ja conté una ID, s'actualitzarà el control a la base de dades.
   * @return bool ``true`` si s'ha desat correctament, ``false`` en cas contrari.
   */
  public function desar() : bool
  {
    $arrayControl = get_object_vars($this);
    if ($this->controlID === null) {
      $arrayControl = $this->estandard($arrayControl);
      return parent::crear($arrayControl);
    }
    unset($arrayControl['controlID']);
    unset($arrayControl['usuari']);
    unset($arrayControl['user']);
    return parent::updatePerId($arrayControl, $this->controlID);
  }
  // GETTERS
  /**
   * Obté les dades de l'usuari que ha realitzat el control.
   * @return bool ``true`` si s'han obtingut correctament les dades, ``false`` en cas contrari.
   */
  public function getDadesUsuari(): bool
  {
    if ($this->usuari == null) {
      return false;
    }
    $this->user = User::trobarPerId($this->usuari);
    return true;
  }
  // ALTRES MÈTODES
  /**
   * Estandarditza les propietats de l'objecte per a ser creat. Eliminar aquelles columnes que tenen valor per defecte a la DB. O bé la propietat user que és l'objecte relacionat.
   * @param array<string, mixed> $data Dades a estandarditzar.
   * @return array<string, mixed>
   */
  private function estandard(array $data): array
  {
    unset($data['controlID']);
    unset($data['data_hora']);
    unset($data['user']);
    return $data;
  }
  /**
   * Comprova si totes les propietats nul·lables de l'objecte són null. Per evitar desar objectes buits a la DB.
   * @return bool
   */
  public function allNull(): bool
  {
    $valorsNullables = ['ph', 'clor', 'alcali', 'temperatura', 'transparent', 'fons'];
    foreach (get_object_vars($this) as $propietat => $valor) {
      if (in_array($propietat, $valorsNullables) && !is_null($valor)) {
        return false;
      }
    }
    return true;
  }
}
