<?php

namespace PoolNET\controller;

use PoolNET\config\{Request, Response};
use PoolNET\interface\Controller\Post;
use PoolNET\service\JwtHandler;
use PoolNET\User;

class AuthLogin implements Post
{
  /**
   * @param array<string, mixed> $body El cos de la petició
   * @return void
   */
  public static function post(Request $req, Response $res): void
  {
    $body = $req->getParsedBody();
    $user = User::trobarPerUnic('usuari', $body['usuari']);
    if (!$user || !$user->checkPswd($body['password'])) {
      $res->withStatus(400)->toJson(["error" => "Error amb les credencials."]);
      return;
    }
    $jwt = new JwtHandler();
    $token = $jwt->jwtEncodeData('piscina', [
      'userID' => $user->userID,
      'usuari' => $user->usuari,
      'nivell' => $user->nivell,
      'email' => $user->getPrivateEmail(),
    ]);

    setcookie("token", $token, [
      "httpOnly" => true,
      "expires" => time() + (10 * 365 * 24 * 60 * 60), // 10 anys des d'ara
      "path" => "/", // Disponible en tot el lloc
      // "secure" => true, // Només disponible a través de HTTPS
      "samesite" => "Strict", // Només disponible per al mateix lloc (no cross-site)
    ]);
    $res->withStatus(200)->toJson(["token" => $token]);
  }
}
