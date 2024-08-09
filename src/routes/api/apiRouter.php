<?php

namespace PoolNET\route\api;

use PoolNET\controller\AuthLogin;
use PoolNET\controller\Control;
use PoolNET\service\RouterJson;

function apiRouter(): RouterJson
{
    $apiRouter = new RouterJson('/api');
    $apiRouter->addController("/control", Control::class);
    $apiRouter->addController("/auth/login", AuthLogin::class);
    return $apiRouter;
}
