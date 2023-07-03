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
  protected static function parseBody( ? array $valors = null)
  {
    return json_decode(file_get_contents('php://input'), true);
  }
  public static function respostaSimple(int $status = 500,  ? array $response = null, bool $headers = true)
  {
    switch ($status) {
      case '405' :
        if ($response === null) {
          $response = array("error" => "Mètode no permès");
        }
        break;

      default :
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
