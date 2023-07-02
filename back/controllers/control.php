<?php
namespace PoolNET\controller;

use PoolNET\config\Database;
use PoolNET\Control as PoolNETControl;
use PoolNET\MW\AuthMW;

class Control extends Controlador
{
  public static function get()
  {
    parent::headers("GET");
    try {
      //Result
      $result = PoolNETControl::trobarMolts(['orderBy' => ['data_hora', 'DESC']], 20);
      $num = count($result);
      $num > 0 ? $res = $result : $res = array('message' => 'No s\'ha trobat cap control');
      parent::respostaSimple(200, $res, false);
    } catch (\Throwable $th) {
      parent::respostaSimple(500, array("error" => $th->getMessage()), false);
    }
  }

  public static function post()
  {
    parent::headers("POST");
    // Init DB & Connect
    $database = new Database();
    $dbcnx = $database->connect();

    $authMW = new AuthMW($dbcnx);
    $auth = $authMW->isValid();

    if ($auth['success']) {
      // Get raw posted data
      $data = json_decode(file_get_contents("php://input"), true);
      // Get user id from token
      $userID = $authMW->jwtDecodeData($_COOKIE['token'])->userID;

      // Validate
      try {
        $control = new PoolNETControl($data);
        $control->usuari = $userID;
      } catch (\Throwable $th) {
        parent::respostaSimple(400, array("error" => $th->getMessage()), false);
        return;
      }

      if ($control->allNull()) {
        parent::respostaSimple(400, array("error" => "Mínim has d'omplir un camp."), false);
        return;
      }

      $controlDesat = $control->desar();
      if ($controlDesat) {
        parent::respostaSimple(204, null, false);
      } else {
        parent::respostaSimple(500, array("error" => "No s'ha pogut desar el control de l'aigua."), false);
      }
    } else {
      parent::respostaSimple(401, array("error" => $auth['message']), false);
    }
  }
}
