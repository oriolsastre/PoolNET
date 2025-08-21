<?php

namespace PoolNET\controller;

use PoolNET\Control as DtoControl;
use PoolNET\interface\Controller\Get;
use PoolNET\interface\Controller\Post;
use PoolNET\interface\config\{Request, Response};
use Throwable;

class Control implements Get
{
  /**
   * @return void
   */
  public function get(Request $req, Response $res): void
  {
    $result = DtoControl::trobarMolts(['orderBy' => ['data_hora', 'DESC']], 20);
    $num = count($result);
    $num > 0 ? $data = $result : $data = ['message' => 'No s\'ha trobat cap control'];
    $res->withStatus(200)->toJson($data);
  }
  // /**
  //  * @param array<string, mixed> $body El cos de la petició
  //  * @return void
  //  */
  // public function post(Request $req, Response $res): void
  // {
  //   $body = $req->getParsedBody();
  //   $userData = json_decode(getenv('JWT_USER_DATA'));
  //   $control = new DtoControl($body);
  //   $control->usuari = (int) $userData->usuariId;
  //   if ($control->allNull()) {
  //     $res->withStatus(400)->toJson(["error" => "Mínim has d'omplir un camp."]);
  //   }
  //   if ($control->desar()) {
  //     $res->withStatus(201)->toJson([]);
  //   } else {
  //     $res->withStatus(500)->toJson(["error" => "No s'ha pogut desar el control de l'aigua."]);
  //   }
  // }
  //   /**
  //    * @param array<string, mixed> $body El cos de la petició
  //    * @return void
  //    */
  //   public static function patch(array $body): void
  //   {
  //     // parent::headers("PATCH");
  //     try {
  //       $userData = json_decode(getenv('JWT_USER_DATA'));
  //       $controlAEditar = DtoControl::trobarPerUnic('controlId', (int) $body['controlId']);
  //       if ($controlAEditar === null) {
  //         // parent::respostaSimple(404, ["error" => "No s'ha trobat el control."], false);
  //       }
  //       $controlAEditar->getDadesUsuari();
  //       if ($controlAEditar->user->usuariId != (int) $userData->usuariId && (int) $userData->nivell > 0) {
  //         // parent::respostaSimple(403, ["error" => "Només pots editar controls propis."], false);
  //       }
  //       foreach ($body as $camp => $valor) {
  //         $controlAEditar->$camp = $valor;
  //       }
  //       if ($controlAEditar->allNull()) {
  //         // parent::respostaSimple(400, ["error" => "No pots buidar un control."], false);
  //       }
  //       if ($controlAEditar->desar()) {
  //         // parent::respostaSimple(204, null, false);
  //       } else {
  //         // parent::respostaSimple(500, ["error" => "No s'ha pogut desar el control."], false);
  //       }
  //     } catch (Throwable $th) {
  //       // parent::respostaSimple(400, ["error" => $th->getMessage()], false);
  //     }
  //   }
  //   /**
  //    * @param array<string, mixed> $body El cos de la petició
  //    * @return void
  //    */
  //   public static function delete(array $body): void
  //   {
  //     // parent::headers("DELETE");
  //     try {
  //       $userData = json_decode(getenv('JWT_USER_DATA'));
  //       $controlAEliminar = DtoControl::trobarPerUnic('controlId', (int) $body['controlId']);
  //       if ($controlAEliminar === null) {
  //         // parent::respostaSimple(404, ["error" => "No s'ha trobat el control."], false);
  //       }
  //       $controlAEliminar->getDadesUsuari();
  //       if ($controlAEliminar->user->usuariId != (int) $userData->usuariId && (int) $userData->nivell > 0) {
  //         // parent::respostaSimple(403, ["error" => "Només pots eliminar controls propis."], false);
  //       }
  //       if ($controlAEliminar->borrar()) {
  //         // parent::respostaSimple(204, null, false);
  //       } else {
  //         // parent::respostaSimple(500, ["error" => "No s'ha pogut borrar el control."], false);
  //       }
  //     } catch (Throwable $th) {
  //       // parent::respostaSimple(400, ["error" => $th->getMessage()], false);
  //     }
  //   }
}
