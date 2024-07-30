<?php

namespace PoolNET\service;

use stdClass;

class Router
{
  public ?string $prefix;
  protected string $format;
  protected stdClass $_routers;
  protected stdClass $_routes;
  public function __construct(?string $prefix = null, string $format = "json")
  {
    $this->prefix = $prefix;
    $this->format = $format;
    $this->_routers = new stdClass();
    $this->_routes = new stdClass();
  }

  public function addRouter(string $path, Router $router): void
  {
    $this->_routers->$path = $router;
  }

  public function use(string $path, ?string $params, string $method): void
  {
    $path = $this->removePrefix($path);
    $path = $this->removeClosingSlash($path);
    foreach ($this->_routers as $routerPath => $router) {
      if (str_starts_with($path, $routerPath)) {
        $router->use($path, $params, $method);
        return;
      }
    }
  }

  protected function removePrefix(string $string): string
  {
    if (0 === strpos($string, $this->prefix)) {
      $string = substr($string, strlen($this->prefix));
    }
    return $string;
  }
  protected function removeClosingSlash(string $string): string
  {
    return strlen($string) > 1 ? rtrim($string, "/") : $string;
  }
  protected function getSuccessiveRoutes(string $path): array
  {
    $parts = explode('/', $path);
    $currentPath = '';
    $successiveRoutes = [];
    foreach ($parts as $part) {
      $currentPath .= '/' . $part;
      $currentPath = str_replace("//", "/", $currentPath);
      $successiveRoutes[] = $currentPath;
    }
    return $successiveRoutes;
  }
}
