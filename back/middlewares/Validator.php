<?php
namespace PoolNET\MW;

use PoolNET\controller\Controlador;
use ReflectionClass;

class Validator extends Controlador
{
  public static function validateBodyWithClass(array $body, string $class): void
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
