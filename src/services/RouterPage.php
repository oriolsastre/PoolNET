<?php

namespace PoolNET\service;

use PoolNET\config\Request;
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

  public function use(Request $req): void
  {
    $path = $this->removePrefix($req->routerPath);
    $path = $this->removeClosingSlash($path);
    parent::use($req);
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
