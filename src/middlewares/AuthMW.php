<?php

namespace PoolNET\MW;

use PoolNET\User;
use PoolNET\error\Forbidden;
use PoolNET\interface\Middleware;
use PoolNET\interface\config\Request;
use PoolNET\interface\config\Response;
use PoolNET\service\JwtHandler;

class AuthMW implements Middleware
{
  private JwtHandler $jwt;
  public function __construct(private int $nivell = 0)
  {
    $this->jwt = new JwtHandler();
  }
  public function use(Request &$req, Response &$res): bool
  {
    $jwt_token = $req->getCookieParams()['token'] ?? null;
    if ($jwt_token === null) {
      throw new Forbidden();
    }
    $data = $this->jwt->jwtDecodeData($jwt_token);
    if (!isset($data->usuariId)) {
      throw new Forbidden();
    }
    $user = User::trobarPerId($data->usuariId);
    if ($user === null) {
      throw new Forbidden();
    }
    if ($user->getNivell() < $this->nivell) {
      throw new Forbidden();
    }
    // TODO: Posar l'usuari al req
    return true;
  }
}
