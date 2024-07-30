<?php

declare(strict_types=1);
require_once __DIR__ . '/src/vendor/autoload.php';
require_once __DIR__ . '/src/routes/index.php';

use PoolNET\config\Env;
use PoolNET\service\Router;

Env::executar();
$uri = explode('/PoolNET', $_SERVER['REQUEST_URI'])[1];
$route = explode('?', $uri)[0];
$params = explode('?', $uri)[1] ?? null;

$router = new Router("", "html");
$router->addRouter("/api", $apiRouter);
$router->addRouter("/", $pageRouter);

$router->use($route, $params, strtolower($_SERVER['REQUEST_METHOD']));
