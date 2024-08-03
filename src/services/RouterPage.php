<?php

namespace PoolNET\service;

use PoolNET\service\Page;
use PoolNET\service\Router;
use stdClass;

class RouterPage extends Router
{
  private stdClass $pages;
  public function __construct(string $prefix)
  {
    parent::__construct($prefix, "html");
    $this->pages = new stdClass();
  }

  public function addPage(string $path, Page $page): void
  {
    $this->pages->$path = $page;
  }

  public function use(string $path, ?string $params, string $method): void
  {
    $path = $this->removePrefix($path);
    $path = $this->removeClosingSlash($path);
    parent::use($path, $params, $method);
    $routes = $this->getSuccessiveRoutes($path);
    foreach ($routes as $route) {
      if (isset($this->pages->$route)) {
        $page = $this->pages->$route;
        if ($page instanceof Page) {
          $page->render();
          return;
        }
      }
    }
  }
}
