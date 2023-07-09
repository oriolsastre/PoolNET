<?php
namespace PoolNET\MW;

use PoolNET\controller\Controlador;
use PoolNET\JwtHandler;
use PoolNET\User;

class AuthMW extends Controlador
{
  private static ?JwtHandler $jwt = null;
  /**
   * Inicia el JWTHandler
   * @return void
   */
  private static function initJwtHandler(): void
  {
    self::$jwt = new JwtHandler();
  }
  /**
   * Valida el token rebut a la cookie de la petició.
   * @return boolean
   */
  private static function isValid(): bool
  {
    if (!isset($_COOKIE['token'])) {
      return false;
    }
    if (self::$jwt === null) {
      self::initJwtHandler();
    }
    $data = self::$jwt->jwtDecodeData($_COOKIE['token']);
    if (!isset($data->userID)) {
      return false;
    }
    $user = User::trobarPerId((int) $data->userID);
    if ($user === null) {
      return false;
    }
    return true;

  }
  /**
   * Comprova el token rebut a les cookies de la petició i permet seguir si aquest és vàlid. Si no, atura la petició amb un 401.
   * @return void Les dades de l'usuari són afegides a la variable d'entorn JWT_USER_DATA
   */
  public static function rutaProtegida(): void
  {
    if (parent::$dbcnx === null) {
      parent::connect();
    }

    if (!self::isValid()) {
      self::respostaSimple(401, ["error" => "No autoritzat"], true);
    }
    if (self::$jwt === null) {
      self::initJwtHandler();
    }
    $userData = self::$jwt->jwtDecodeData($_COOKIE['token']);
    putenv('JWT_USER_DATA=' . json_encode($userData));
  }
}
