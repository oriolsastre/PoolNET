<?php
namespace PoolNET\service;

use PoolNET\service\Page;
use PoolNET\service\Router;
use stdClass;

class RouterPage extends Router
{
  private stdClass $_pages;
  public function __construct(string $prefix)
  {
    parent::__construct($prefix, "html");
    $this->_pages = new stdClass();
  }

  public function addPage(string $path, Page $page): void
  {
    $this->_pages->$path = $page;
  }

  public function use (string $path, ?string $params, string $method): void
  {
    $path = $this->removePrefix($path);
    $path = $this->removeClosingSlash($path);
    parent::use ($path, $params, $method);
    $routes = $this->getSuccessiveRoutes($path);
    foreach ($routes as $route) {
      if (isset($this->_pages->$route)) {
        $page = $this->_pages->$route;
        if ($page instanceof Page) {
          $page->render();
          return;
        }
      }
    }
  }
}
