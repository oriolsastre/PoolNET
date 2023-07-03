<?php
namespace PoolNET\MW;

use PoolNET\controller\Controlador;
use PoolNET\JwtHandler;
use PoolNET\User;

class AuthMW extends Controlador
{
  private static $jwt = null;
  private static function initJwtHandler()
  {
    self::$jwt = new JwtHandler();
  }
  private static function isValid()
  {
    if (isset($_COOKIE['token'])) {
      if (self::$jwt === null) {
        self::initJwtHandler();
      }
      $data = self::$jwt->jwtDecodeData($_COOKIE['token']);
      if (isset($data->userID)) {
        $user = User::trobarPerId($data->userID);
        if ($user != null) {
          return [
            "success" => true,
          ];
        }
      }
      return [
        "success" => false,
        "message" => $data,
      ];
    } else {
      return [
        "success" => false,
        "message" => "No autoritzat",
      ];
    }
  }
  public static function rutaProtegida()
  {
    if (parent::$dbcnx === null) {
      parent::connect();
    }
    $auth = self::isValid();
    if ($auth['success'] === false) {
      self::respostaSimple(401, array("error" => $auth['message']), true);
      exit;
    }
    if (self::$jwt === null) {
      self::initJwtHandler();
    }
    $userData = self::$jwt->jwtDecodeData($_COOKIE['token']);
    putenv('JWT_USER_DATA=' . json_encode($userData));
  }
}
