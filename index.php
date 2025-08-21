<?php

declare(strict_types=1);
require_once __DIR__ . '/src/vendor/autoload.php';

use PoolNET\config\{Request, Response, Env};
use PoolNET\service\Router;

use function PoolNET\route\api\apiRouter;
use function \PoolNET\route\pageRouter;

Env::executar();
$req = new Request();
$res = new Response();

$router = new Router("", "html");
$router->addRouter("/api", apiRouter());
$router->addRouter("/", pageRouter());

$router->use($req, $res);
