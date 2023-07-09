<?php
namespace PoolNET\MW;

use PoolNET\controller\Controlador;
use ReflectionClass;

class Validator extends Controlador
{
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
            );
          }
        } else {
          self::respostaSimple(
            400,
            array(
              "error" => "Falta algun camp obligatori.",
              "camps_obligatoris" => $obligatori,
            ),
          );
        }
      }
    }
    return $body;
  }
  public static function validateBodyWithClass(array $body, string $class) : void
  {
    $reflector = new ReflectionClass($class);
    foreach ($body as $property => $value) {
      $classProperty = $reflector->getProperty($property);
      if (
        ($value === null && !$classProperty->getType()->allowsNull()) ||
        ($value !== null && get_debug_type($value) !== $classProperty->getType()->getName())
      ) {
        parent::respostaSimple(400, array(
          "error" => "El camp '" . $property . "' no pot ser '" . gettype($value) . "'. Hauria de ser '" . $classProperty->getType() . "'.",
        ));
      }
    }
  }
}
