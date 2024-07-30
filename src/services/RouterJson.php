<?php

namespace PoolNET\service;

use InvalidArgumentException;
use PoolNET\service\Router;
use stdClass;

class RouterJson extends Router
{
  private stdClass $_controllers;
  public function __construct(string $prefix)
  {
    parent::__construct($prefix, "json");
    $this->_controllers = new stdClass();
  }

  public function addController(string $path, string | Controlador $controlador, string $metode = "GET"): void
  {
    if (is_string($controlador) && !class_exists($controlador)) {
      throw new InvalidArgumentException("Aquest controlador no existeix");
    }
    $this->_controllers->$path = $controlador;
  }

  public function use(string $path, ?string $params, string $method): void
  {
    $path = $this->removePrefix($path);
    $path = $this->removeClosingSlash($path);
    parent::use($path, $params, $method);
    $routes = $this->getSuccessiveRoutes($path);
    foreach ($routes as $route) {
      if (isset($this->_routes->$route)) {
        $controller = $this->_routes->$route;
        if ($controller instanceof Controlador) {
          if (method_exists($controller, $method)) {
            $controller->$method($params);
            return;
          }
        } elseif (gettype($controller) === "string" and class_exists($controller)) {
          if (method_exists($controller, $method)) {
            $controller::$method($params);
            return;
          }
        }
      }
    }
  }
}
