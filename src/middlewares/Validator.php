<?php

namespace PoolNET\MW;

use PoolNET\config\Request;
use PoolNET\interface\Middleware;
use PoolNET\service\Controlador;
use ReflectionClass;

class Validator extends Controlador
{
  /**
   * Parseja el cos de la petició i el retorna com a array.
   * @param Request $req
   * @param array<string, mixed>|null $obligatori [Opcional] Valors necessaris que han der ser al cos de la petició i el seu tipus. Per exemple, ``['controlID' => 'integer']``.
   * @return array Cos de la petició parsejat.
   */
  public static function requiredFields(Request $req, array $obligatori): void
  {
    $body = $req->getParsedBody();
    if ($obligatori !== null) {
      foreach ($obligatori as $param => $tipus) {
        if (isset($body[$param])) {
          if (gettype($body[$param]) !== $tipus) {
            self::respostaSimple(
              400,
              [
                "error" => "Algun camp no és del tipus correcte.",
                "camps_obligatoris" => $obligatori,
              ],
            );
          }
        } else {
          self::respostaSimple(
            400,
            [
              "error" => "Falta algun camp obligatori.",
              "camps_obligatoris" => $obligatori,
            ],
          );
        }
      }
    }
  }
  /**
   * Valida els valors del cos de la petició amb els tipus que admet la classe passada com a paràmetre.
   * @param array<string, mixed> $body El cos de la petició.
   * @param string $class Classe que ha de validar els paràmetres.
   * @return void Respon amb 400 si algun valor no és compatible.
   */
  public static function validateBodyWithClass(array $body, string $class): void
  {
    $reflector = new ReflectionClass($class);
    foreach ($body as $property => $value) {
      $classProperty = $reflector->getProperty($property);
      if (
        (($value === null && !$classProperty->getType()->allowsNull()) ||
          ($value !== null && get_debug_type($value) !== $classProperty->getType()->getName())) &&
        !(get_debug_type($value) == "int" && $classProperty->getType()->getName() == "float")
      ) {
        parent::respostaSimple(400, [
          "error" => "El camp '" . $property . "' no pot ser '" . gettype($value) . "'. Hauria de ser '" . $classProperty->getType() . "'.",
        ]);
      }
    }
  }
}

class LoginValidator extends Validator implements Middleware
{
  public static function use(Request $req): void
  {
    self::requiredFields($req, ["usuari" => "string", "password" => "string"]);
  }
}
