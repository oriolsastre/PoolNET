<?php
namespace PoolNET\router;

use stdClass;

class Router
{
  public stdClass $routes;
  public function __construct()
  {
    $this->routes = new stdClass();
  }
  public function addRoute(string $path, string $controller, string $method = "GET"): void
  {
    $this->routes->{$path} = $controller;
  }

  public function use (?string $route): void
  {
    if ($route === null) {
      $controller = 'main.php';
    } else if (isset($this->routes->{$route})) {
      $controller = $this->routes->{$route};
    } else {
      $controller = 'main.php';
    }
    require_once './src/routes/' . $controller;
  }
}
