<?php

namespace PoolNET\controller;

use PoolNET\config\Env;

class Controlador
{
  protected static function headers(?string $allowMethod = "GET")
  {
    Env::executar();
    header('Access-Control-Allow-Origin: ' . getenv('ENV_HEADERS_ALLOW_ORIGIN'));
    header('Access-Control-Allow-Methods: ' . $allowMethod);
    header('Access-Control-Allow-Headers: ' . getenv('ENV_HEADERS_ALLOW_HEADERS'));
    header('Content-Type: application/json');
  }

  public static function respostaSimple(int $status = 500,  ? array $response = null, bool $headers = true)
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
  }
}
