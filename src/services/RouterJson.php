<?php

namespace PoolNET\service;

use InvalidArgumentException;
use PoolNET\service\Router;
use stdClass;

class RouterJson extends Router
{
  private stdClass $controllers;
  public function __construct(string $prefix)
  {
    parent::__construct($prefix, "json");
    $this->controllers = new stdClass();
  }

  public function addController(string $path, string | Controlador $controlador, string $metode = "GET"): void
  {
    if (is_string($controlador) && !class_exists($controlador)) {
      throw new InvalidArgumentException("Aquest controlador no existeix");
    }
    $this->controllers->$path = $controlador;
  }

  public function use(string $path, ?string $params, string $method): void
  {
    $path = $this->removePrefix($path);
    $path = $this->removeClosingSlash($path);
    parent::use($path, $params, $method);
    $routes = $this->getSuccessiveRoutes($path);
    foreach ($routes as $route) {
      if (isset($this->routes->$route)) {
        $controller = $this->routes->$route;
        if ($controller instanceof Controlador) {
          if (method_exists($controller, $method)) {
            $controller->$method($params);
            return;
          }
        } elseif (gettype($controller) === "string" && class_exists($controller)) {
          if (method_exists($controller, $method)) {
            $controller::$method($params);
            return;
          }
        }
      }
    }
  }
}
