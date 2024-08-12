<?php

namespace PoolNET\service;

use PoolNET\config\{Request, Response};
use PoolNET\interface\Controller\{Controlador, Get, Post, Patch, Delete};
use PoolNET\interface\Middleware;
use PoolNET\service\Router;

class RouterJson extends Router
{
  private array $controllers;
  public function __construct(string $prefix)
  {
    parent::__construct($prefix, "json");
    $this->controllers = array();
  }

  private function addController(string $path, string $method, Controlador $controlador, ?MiddlewareArray $middlewares = null): void
  {
    if (!isset($this->controllers[$path])) {
      $this->controllers[$path] = array();
    }
    $this->controllers[$path][$method] = array("controller" => $controlador, "middlewares" => $middlewares);
  }

  public function get(string $path, Get $controlador, ?MiddlewareArray $middlewares = null): void
  {
    $this->addController($path, "get", $controlador, $middlewares);
  }

  public function post(string $path, Post $controlador, ?MiddlewareArray $middlewares = null): void
  {
    $this->addController($path, "post", $controlador, $middlewares);
  }

  public function patch(string $path, Patch $controlador, ?MiddlewareArray $middlewares = null): void
  {
    $this->addController($path, "patch", $controlador, $middlewares);
  }

  public function delete(string $path, Delete $controlador, ?MiddlewareArray $middlewares = null): void
  {
    $this->addController($path, "delete", $controlador, $middlewares);
  }

  public function use(Request $req, Response $res): bool
  {
    if (!$this->useRouter($req, $res)) {
      if (!$this->useController($req, $res)) {
        $res->withStatus(404)->toJson(["error" => "Ruta no trobada"]);
        return true;
      }
      return false;
    }
    return true;
  }
  protected function useController(Request $req, Response $res): bool
  {
    $route = $this->removePrefix($req->getPath());
    if (isset($this->controllers[$route])) {
      $controller = $this->controllers[$route];
      if (!$this->useMethod($req, $res, $controller)) {
        $res->withStatus(405)->toJson(null);
      }
      return true;
    }
    return false;
  }
  protected function useMethod(Request $req, Response $res, array $controller): bool
  {
    $method = $req->getMethod();
    if (isset($controller[$method])) {
      $mwArray = $controller[$method]["middlewares"];
      if ($this->useMw($req, $res, $mwArray)) {
        $controller[$method]["controller"]::$method($req, $res);
      }
      return true;
    }
    return false;
  }

  protected function useMw(Request $req, Response $res, ?MiddlewareArray $mwArray): bool
  {
    if ($mwArray === null) {
      return true;
    }
    foreach ($mwArray as $mv) {
      /** @var Middleware $mw */
      $result = $mv->use($req, $res);
      if (!$result) {
        return false;
      }
    }
    return true;
  }
}
