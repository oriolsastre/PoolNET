<?php
namespace PoolNET\controller;

use PoolNET\Control as PoolNETControl;

class Control extends Controlador
{
  /**
   * @return void
   */
  public static function get(): void
  {
    parent::headers("GET");
    try {
      $result = PoolNETControl::trobarMolts(['orderBy' => ['data_hora', 'DESC']], 20);
      $num = count($result);
      $num > 0 ? $res = $result : $res = ['message' => 'No s\'ha trobat cap control'];
      parent::respostaSimple(200, $res, false);
    } catch (\Throwable $th) {
      parent::respostaSimple(500, ["error" => $th->getMessage()], false);
    }
  }
  /**
   * @param array<string, mixed> $body El cos de la petició
   * @return void
   */
  public static function post(array $body): void
  {
    parent::headers("POST");
    $userData = json_decode(getenv('JWT_USER_DATA'));
    try {
      $control = new PoolNETControl($body);
      $control->usuari = (int) $userData->userID;
      if ($control->allNull()) {
        parent::respostaSimple(400, ["error" => "Mínim has d'omplir un camp."], false);
      }
      if ($control->desar()) {
        parent::respostaSimple(204, null, false);
      } else {
        parent::respostaSimple(500, ["error" => "No s'ha pogut desar el control de l'aigua."], false);
      }
    } catch (\Throwable $th) {
      parent::respostaSimple(400, ["error" => $th->getMessage()], false);
    }
  }
  /**
   * @param array<string, mixed> $body El cos de la petició
   * @return void
   */
  public static function patch(array $body): void
  {
    parent::headers("PATCH");
    try {
      $userData = json_decode(getenv('JWT_USER_DATA'));
      $controlAEditar = PoolNETControl::trobarPerUnic('controlID', (int) $body['controlID']);
      if ($controlAEditar === null) {
        parent::respostaSimple(404, ["error" => "No s'ha trobat el control."], false);
      }
      $controlAEditar->getDadesUsuari();
      if ($controlAEditar->user->userID != (int) $userData->userID && (int) $userData->nivell > 0) {
        parent::respostaSimple(403, ["error" => "Només pots editar controls propis."], false);
      }
      foreach ($body as $camp => $valor) {
        $controlAEditar->$camp = $valor;
      }
      if ($controlAEditar->allNull()) {
        parent::respostaSimple(400, ["error" => "No pots buidar un control."], false);
      }
      if ($controlAEditar->desar()) {
        parent::respostaSimple(204, null, false);
      } else {
        parent::respostaSimple(500, ["error" => "No s'ha pogut desar el control."], false);
      }
    } catch (\Throwable $th) {
      parent::respostaSimple(400, ["error" => $th->getMessage()], false);
    }
  }
  /**
   * @param array<string, mixed> $body El cos de la petició
   * @return void
   */
  public static function delete(array $body): void
  {
    parent::headers("DELETE");
    try {
      $userData = json_decode(getenv('JWT_USER_DATA'));
      $controlAEliminar = PoolNETControl::trobarPerUnic('controlID', (int) $body['controlID']);
      if ($controlAEliminar === null) {
        parent::respostaSimple(404, ["error" => "No s'ha trobat el control."], false);
      }
      $controlAEliminar->getDadesUsuari();
      if ($controlAEliminar->user->userID != (int) $userData->userID && (int) $userData->nivell > 0) {
        parent::respostaSimple(403, ["error" => "Només pots eliminar controls propis."], false);
      }
      if ($controlAEliminar->borrar()) {
        parent::respostaSimple(204, null, false);
      } else {
        parent::respostaSimple(500, ["error" => "No s'ha pogut borrar el control."], false);
      }
    } catch (\Throwable $th) {
      parent::respostaSimple(400, ["error" => $th->getMessage()], false);
    }
  }
}
