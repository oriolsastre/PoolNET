<?php

namespace PoolNET\service;

use PoolNET\config\Request;
use PoolNET\config\Response;
use PoolNET\interface\Middleware;
use stdClass;

class Router
{
  public ?string $prefix;
  protected string $format;
  protected stdClass $routers;
  public function __construct(?string $prefix = null, string $format = "json")
  {
    $this->prefix = $prefix;
    $this->format = $format;
    $this->routers = new stdClass();
  }

  public function addRouter(string $path, Router $router): void
  {
    $this->routers->$path = $router;
  }

  public function use(Request $req, Response $res): void
  {
    $path = $this->removePrefix($req->routerPath);
    $req->routerPath = $path;
    /** @var Router $router */
    foreach ($this->routers as $routerPath => $router) {
      if (str_starts_with($path, $routerPath)) {
        $router->use($req, $res);
        return;
      }
    }
  }

  protected function removePrefix(string $string): string
  {
    if (0 === strpos($string, $this->prefix)) {
      $string = substr($string, strlen($this->prefix));
    }
    return $this->removeClosingSlash($string);
  }
  private function removeClosingSlash(string $string): string
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
