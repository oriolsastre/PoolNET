<?php

namespace PoolNET\controller;

use PoolNET\config\Database;
use PoolNET\config\Env;

class Controlador
{
  protected static $dbcnx = null;
  protected static function connect()
  {
    $database = new Database();
    self::$dbcnx = $database->connect();
  }
  protected static function headers(?string $allowMethod = "GET")
  {
    Env::executar();
    header('Access-Control-Allow-Origin: ' . getenv('ENV_HEADERS_ALLOW_ORIGIN'));
    header('Access-Control-Allow-Methods: ' . $allowMethod);
    header('Access-Control-Allow-Headers: ' . getenv('ENV_HEADERS_ALLOW_HEADERS'));
    header('Content-Type: application/json');
  }
  public static function parseBody( ? array $obligatori = null)
  {
    $body = json_decode(file_get_contents('php://input'), true);
    if ($obligatori !== null) {
      foreach ($obligatori as $param => $tipus) {
        if (isset($body[$param])) {
          if (gettype($body[$param]) !== $tipus) {
            self::respostaSimple(
              400,
              array(
                "error" => "Algun camp no és del tipus correcte.",
                "camps_obligatoris" => $obligatori,
              ),
              false
            );
          }
        } else {
          self::respostaSimple(
            400,
            array(
              "error" => "Falta algun camp obligatori.",
              "camps_obligatoris" => $obligatori,
            ),
            false
          );
        }

      }
    }
    return $body;
  }
  public static function respostaSimple(int $status = 500,  ? array $response = null, bool $headers = true) : void
  {
    switch ($status) {
      case '405' :
        if ($response === null) {
          $response = array("error" => "Mètode no permès");
        }
        break;

      default:
        if ($response === null) {
          $response = array("error" => "Alguna cosa ha fallat");
        }
        break;
    }
    if ($headers) {
      self::headers('*');
    }

    http_response_code($status);
    echo json_encode(
      $response
    );
    exit;
  }
}
