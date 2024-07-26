<?php

declare(strict_types=1);
require_once __DIR__ . '/src/vendor/autoload.php';

use PoolNET\config\Env;
use PoolNET\router\Router;

Env::executar();
$uri = explode('/PoolNET', $_SERVER['REQUEST_URI'])[1];
$route = explode('?', $uri)[0];
$params = explode('?', $uri)[1] ?? null;

$router = new Router();
$router->addRoute('/', 'main.php');
$router->addRoute('/api/control', 'api/control/index.php');
$router->addRoute('/api/accio', 'api/accio/index.php');
$router->addRoute('/api/auth/login', 'api/auth/login/index.php');

$router->use($route, $params);
