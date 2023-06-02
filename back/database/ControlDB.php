<?php
require_once __DIR__ . '/../database/Model.php';

class ControlDB extends Model
{
  private static $table = 'piscinaControl';
  private static $idKey = 'controlID';

  private static function estandard(array $arrayControl)
  {
    unset($arrayControl['controlID']);
    unset($arrayControl['data_hora']);
    unset($arrayControl['user']);
    return $arrayControl;
  }

  public static function crear(Control $control)
  {
    $arrayControl = get_object_vars($control);
    $arrayStdControl = self::estandard($arrayControl);
    // Les dades haurien de venir validades
    return parent::createDB(self::$table, $arrayStdControl);
  }

  public static function trobarId(int $id)
  {
    parent::findById(self::$table, self::$idKey, $id);
  }

  public static function trobarTots(int $limit = 20)
  {
    $totsAssoc = parent::findAll(self::$table, null, $limit);
    if($totsAssoc["success"]){
      return [
        "success" => true,
        "data" => array_map(function ($control) {
          return new Control($control);
          // Afegir objecte user
        }, $totsAssoc["data"])
      ];
    } else {
      return [
        "success" => false,
      ];
    }
    
  }
}
