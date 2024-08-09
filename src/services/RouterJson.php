<?php

namespace PoolNET\service;

use InvalidArgumentException;
use PoolNET\config\Request;
use PoolNET\config\Response;
use PoolNET\service\Router;
use PoolNET\service\Controlador;
use stdClass;

class RouterJson extends Router
{
  private stdClass $controllers;
  public function __construct(string $prefix)
  {
    parent::__construct($prefix, "json");
    $this->controllers = new stdClass();
  }

  public function addController(string $path, string $controlador): void
  {
    if (!class_exists($controlador) || !is_subclass_of($controlador, Controlador::class, true)) {
      throw new InvalidArgumentException("Aquest controlador " . $controlador . " no existeix");
    }
    $this->controllers->$path = $controlador;
  }

  public function use(Request $req, Response $res): void
  {
    $path = $this->removePrefix($req->routerPath);
    parent::use($req, $res);
    $routes = $this->getSuccessiveRoutes($path);
    foreach ($routes as $route) {
      if (isset($this->controllers->$route)) {
        /** @var Controlador $controller */
        $controller = $this->controllers->$route;
        $method = $req->getMethod();
        if (method_exists($controller, $method)) {
          $controller::$method($req, $res);
          return;
        }
      }
    }
  }
}
