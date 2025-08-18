<?php

namespace PoolNET\service;

use PoolNET\interface\config\{Request, Response};
use PoolNET\interface\Middleware;
use stdClass;

class Router
{
  public ?string $prefix;
  protected ?string $prefixLlarg;
  protected string $format;
  protected stdClass $routers;
  public function __construct(?string $prefix = null, string $format = "json")
  {
    $this->prefix = $prefix;
    $this->prefixLlarg = $prefix;
    $this->format = $format;
    $this->routers = new stdClass();
  }

  public function addRouter(string $path, Router $router, Middleware|MiddlewareArray $middleware = null): void
  {
    $this->routers->$path = $router;
    $router->prefixLlarg = $this->removeClosingSlash($this->prefixLlarg) . $router->prefix;
  }

  public function use(Request $req, Response $res): bool
  {
    return $this->useRouter($req, $res);
  }

  protected function useRouter(Request $req, Response $res): bool
  {
    $path = $this->removePrefix($req->getPath());
    foreach ($this->routers as $routerPath => $router) {
      if (str_starts_with($path, $routerPath)) {
        /** @var Router $router */
        $router->use($req, $res);
        return true;
      }
    }
    return false;
  }

  protected function removePrefix(string $string): string
  {
    if (0 === strpos($string, $this->prefixLlarg)) {
      $string = substr($string, strlen($this->prefixLlarg));
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
