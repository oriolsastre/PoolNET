<?php
namespace PoolNET\controller;

use PoolNET\JwtHandler;
use PoolNET\User;

class AuthLogin extends Controlador
{
  public static function post()
  {
    parent::headers("POST");
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->usuari) || !isset($data->password)) {
      parent::respostaSimple(400, array("error" => "Error amb les credencials."), false);
    }

    $user = User::trobarPerUnic('usuari', $data->usuari);

    if (!$user || !$user->checkPswd($data->password)) {
      parent::respostaSimple(400, array("error" => "Error amb les credencials."), false);
    }

    $jwt = new JwtHandler();
    $token = $jwt->jwtEncodeData('piscina', array(
      'userID' => $user->userID,
      'usuari' => $user->usuari,
      'nivell' => $user->nivell,
      'email' => $user->getPrivateEmail(),
    ));

    http_response_code(200);
    setcookie("token", $token, [
      "httpOnly" => true,
      "expires" => time() + (10 * 365 * 24 * 60 * 60), // 10 anys des d'ara
      "path" => "/", // Disponible en tot el lloc
      // "secure" => true, // Només disponible a través de HTTPS
      "samesite" => "Strict", // Només disponible per al mateix lloc (no cross-site)
    ]);
    echo json_encode(
      array("token" => $token)
    );
  }
}
