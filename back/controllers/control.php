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
  public static function post()
  {
    parent::headers("POST");
    // Get raw posted data
    $data = parent::parseBody();
    // Get user id from token
    $userData = json_decode(getenv('JWT_USER_DATA'));

    // Validate
    try {
      $control = new PoolNETControl($data);
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
  public static function patch()
  {
    parent::headers("PATCH");
    try {
      $valorsObligatoris = array('controlID' => "integer");
      $data = parent::parseBody($valorsObligatoris);
      $userData = json_decode(getenv('JWT_USER_DATA'));
      $controlAEditar = PoolNETControl::trobarPerUnic('controlID', $data['controlID']);
      if ($controlAEditar === null) {
        parent::respostaSimple(404, array("error" => "No s'ha trobat el control."), false);
      }
      $controlAEditar->getDadesUsuari();
      if ($controlAEditar->user->userID != $userData->userID && $userData->nivell > 0) {
        parent::respostaSimple(403, array("error" => "Només pots editar controls propis."), false);
      }
      // Editar
      foreach ($data as $camp => $valor) {
        $controlAEditar->$camp = $valor;
      }
      if ($controlAEditar->allNull()) {
        parent::respostaSimple(400, array("error" => "No pots buidar un control."), false);
      }
      // echo $valorsObligatoris['controlID'];
      $controlAEditar->desar();
    } catch (\Throwable $th) {
      parent::respostaSimple(400, array("error" => $th->getMessage()), false);
    }
  }
  public static function delete()
  {
    parent::headers("DELETE");
    try {
      $valorsObligatoris = array('controlID' => "integer");
      $data = parent::parseBody($valorsObligatoris);
      $userData = json_decode(getenv('JWT_USER_DATA'));
      $controlAEliminar = PoolNETControl::trobarPerUnic('controlID', $data['controlID']);
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
