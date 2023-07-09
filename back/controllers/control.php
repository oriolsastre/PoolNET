<?php
namespace PoolNET\controller;

use PoolNET\Control as PoolNETControl;

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
  public static function post($body)
  {
    parent::headers("POST");
    // Get user id from token
    $userData = json_decode(getenv('JWT_USER_DATA'));

    try {
      $control = new PoolNETControl($body);
      $control->usuari = $userData->userID;
      if ($control->allNull()) {
        parent::respostaSimple(400, array("error" => "Mínim has d'omplir un camp."), false);
      }
      $controlDesat = $control->desar();
      if ($controlDesat) {
        parent::respostaSimple(204, null, false);
      } else {
        parent::respostaSimple(500, array("error" => "No s'ha pogut desar el control de l'aigua."), false);
      }
    } catch (\Throwable $th) {
      parent::respostaSimple(400, array("error" => $th->getMessage()), false);
    }
  }
  public static function patch($body)
  {
    parent::headers("PATCH");
    try {
      $userData = json_decode(getenv('JWT_USER_DATA'));
      $controlAEditar = PoolNETControl::trobarPerUnic('controlID', $body['controlID']);
      if ($controlAEditar === null) {
        parent::respostaSimple(404, array("error" => "No s'ha trobat el control."), false);
      }
      $controlAEditar->getDadesUsuari();
      if ($controlAEditar->user->userID != $userData->userID && $userData->nivell > 0) {
        parent::respostaSimple(403, array("error" => "Només pots editar controls propis."), false);
      }
      // Editar
      foreach ($body as $camp => $valor) {
        $controlAEditar->$camp = $valor;
      }
      if ($controlAEditar->allNull()) {
        parent::respostaSimple(400, array("error" => "No pots buidar un control."), false);
      }
      if ($controlAEditar->desar()) {
        parent::respostaSimple(204, null, false);
      } else {
        parent::respostaSimple(500, array("error" => "No s'ha pogut desar el control."), false);
      }
    } catch (\Throwable $th) {
      parent::respostaSimple(400, array("error" => $th->getMessage()), false);
    }
  }
  public static function delete($body)
  {
    parent::headers("DELETE");
    try {
      $userData = json_decode(getenv('JWT_USER_DATA'));
      $controlAEliminar = PoolNETControl::trobarPerUnic('controlID', $body['controlID']);
      if ($controlAEliminar === null) {
        parent::respostaSimple(404, array("error" => "No s'ha trobat el control."), false);
      }
      $controlAEliminar->getDadesUsuari();
      if ($controlAEliminar->user->userID != $userData->userID && $userData->nivell > 0) {
        parent::respostaSimple(403, array("error" => "Només pots eliminar controls propis."), false);
      }
      $controlAEliminar->borrar();
      parent::respostaSimple(204, null, false);
    } catch (\Throwable $th) {
      parent::respostaSimple(400, array("error" => $th->getMessage()), false);
    }
  }
}
