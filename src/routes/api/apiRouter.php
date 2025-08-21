<?php

namespace PoolNET\route\api;

use PoolNET\MW\LoginValidator;
use PoolNET\controller\{Control, AuthLogin};
use PoolNET\service\MiddlewareArray;
use PoolNET\service\RouterJson;

function apiRouter(): RouterJson
{
    $control = new Control();
    $authLogin = new AuthLogin();

    $validatorMw = new LoginValidator();
    $authLoginMws = new MiddlewareArray();
    $authLoginMws->add($validatorMw);

    $apiRouter = new RouterJson('/api');
    $apiRouter->get("/control", $control);
    $apiRouter->post("/auth/login", $authLogin, $authLoginMws);
    return $apiRouter;
}
