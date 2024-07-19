<?php
namespace PoolNET\controller;

use PoolNET\JwtHandler;
use PoolNET\User;

class AuthLogin extends Controlador
{
  /**
   * @param array<string, mixed> $body El cos de la petició
   * @return void
   */
  public static function post(array $body): void
  {
    parent::headers("POST");
    $user = User::trobarPerUnic('usuari', $body['usuari']);
    if (!$user || !$user->checkPswd($body['password'])) {
      parent::respostaSimple(400, ["error" => "Error amb les credencials."], false);
    }
    $jwt = new JwtHandler();
    $token = $jwt->jwtEncodeData('piscina', [
      'userID' => $user->userID,
      'usuari' => $user->usuari,
      'nivell' => $user->nivell,
      'email' => $user->getPrivateEmail(),
    ]);

    http_response_code(200);
    setcookie("token", $token, [
      "httpOnly" => true,
      "expires" => time() + (10 * 365 * 24 * 60 * 60), // 10 anys des d'ara
      "path" => "/", // Disponible en tot el lloc
      // "secure" => true, // Només disponible a través de HTTPS
      "samesite" => "Strict", // Només disponible per al mateix lloc (no cross-site)
    ]);
    echo json_encode(["token" => $token]);
    exit;
  }
}
