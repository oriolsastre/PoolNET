<?php

namespace PoolNET\controller;

use PDO;
use PoolNET\config\Database;
use PoolNET\config\Env;

class Controlador
{
  protected static ?PDO $dbcnx = null;
  /**
   * Crear una connexió a la base de dades.
   * @return void
   */
  protected static function connect(): void
  {
    $database = new Database();
    self::$dbcnx = $database->connect();
  }
  /**
   * Aplica les capceleres per a les respostes de l'API.
   * @param string|null $allowMethod Mètodes permesos. GET per defecte.
   */
  protected static function headers(?string $allowMethod = "GET"): void
  {
    Env::executar();
    header('Access-Control-Allow-Origin: ' . (string) getenv('ENV_HEADERS_ALLOW_ORIGIN'));
    header('Access-Control-Allow-Methods: ' . $allowMethod);
    header('Access-Control-Allow-Headers: ' . (string) getenv('ENV_HEADERS_ALLOW_HEADERS'));
    header('Content-Type: application/json');
  }
  /**
   * Crea una resposta a la petició.
   * @param int $status Codi d'estat http de la petició.
   * @param array|null $response Resposta.
   * @param bool $headers Si cal aplicar o no capceleres.
   * @return void
   */
  public static function respostaSimple(int $status = 500,  ? array $response = null, bool $headers = true) : void
  {
    switch ($status) {
      case 405:
        if ($response === null) {
          $response = ["error" => "Mètode no permès"];
        }
        break;
      default:
        if ($response === null) {
          $response = ["error" => "Alguna cosa ha fallat"];
        }
        break;
    }
    if ($headers) {
      self::headers('*');
    }

    http_response_code($status);
    echo json_encode($response);
    exit;
  }
}
